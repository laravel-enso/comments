<template>

    <div :class="'box collapsed-box box-' + headerClass">
        <div class="box-header with-border">
            <i class="fa fa-comments-o">
            </i>
            <h3 class="box-title">
                <slot name="comments-manager-title">
                </slot>
            </h3>
             <div class="box-tools pull-right">
                <i v-if="commentsList.length > 1"
                    class="fa fa-search">
                </i>
                <input type="text"
                    size="15"
                    class="comments-filter margin-right-xs"
                    v-model="queryString"
                    v-if="commentsList.length > 1">
                <span class="badge bg-orange">
                    {{ commentsCount }}
                </span>
                <button type="button"
                    class="btn btn-box-tool btn-sm"
                    @click="getData">
                    <i class="fa fa-refresh"></i>
                </button>
                <button class="btn btn-box-tool btn-sm"
                    data-widget="collapse">
                    <i class="fa fa-plus">
                    </i>
                </button>
            </div>
        </div>
        <div class="box-body chat">
            <div class="item"
                v-for="(comment, index) in filteredCommentsList">
                <img :src="comment.owner.avatar_link"
                    alt="user image"
                    class="offline">
                <p class="message">
                <small class="text-muted pull-right">
                    <span v-if="comment.is_edited">
                        {{ editedLabel }}
                    </span>
                    <span v-else>
                        {{ postedLabel }}
                    </span>
                    <i class="fa fa-clock-o">
                    </i> {{ comment.updated_at | timeFromNow }}
                    <transition-group name="fade" mode="out-in" v-if="comment.is_editable">
                        <i class="btn btn-xs btn-warning fa fa-pencil-square-o margin-right-xs"
                            :key="'edit-' + index"
                            @click="editedCommentIndex = index;taggedUsers=comment.tagged_users_list"
                            v-if="editedCommentIndex === null">
                        </i>
                        <i class="btn btn-xs btn-danger fa fa-trash-o"
                            :key="'delete-' + index"
                            @click="deleteComment(index)"
                            v-if="editedCommentIndex === null">
                        </i>
                        <i class="btn btn-xs btn-success fa fa-check"
                            @click="updateComment(comment)"
                            :key="'update-' + index"
                            v-if="editedCommentIndex === index && comment.body.trim()">
                        </i>
                    </transition-group>
                    </small>
                    <a href="#"
                        class="name">
                        {{ comment.owner.full_name }}
                    </a>
                    <span v-html="highlightTaggedUsers(comment)"
                        v-if="editedCommentIndex !== index">
                    </span>
                    <textarea class="form-control comment"
                        v-focus
                        v-inputor-on-focus
                        v-if="editedCommentIndex === index"
                        v-model="comment.body">
                    </textarea>
                </p>
            </div>
            <center>
                <small style="cursor: pointer;color: #909090"
                    @click="getData"
                    v-if="commentsList.length">
                    <slot name="comments-manager-load-more"></slot>
                </small>
            </center>
        </div>
        <div class="box-footer" v-if="!editedCommentIndex">
            <div class="input-group">
                <textarea class="form-control comment"
                    v-inputor-on-focus
                    :placeholder="placeholder"
                    v-model="commentInputValue"
                    :id="'textarea-' + _uid">
                </textarea>
                <div class="input-group-btn">
                    <button type="button"
                            class="btn btn-success"
                            @click="addComment()">
                    <i class="fa fa-check"
                        v-if="isValidComment">
                    </i>
                    <i class="fa fa-ellipsis-h"
                        v-else>
                    </i>
                    </button>
                </div>
            </div>
        </div>
        <div class="overlay" v-if="loading">
            <i class="fa fa-spinner fa-spin spinner-custom" ></i>
        </div>
    </div>

</template>

<script>

    export default {
        props: {
            id: {
                type: Number,
                required: true
            },
            type: {
                type: String,
                required: true
            },
            headerClass: {
                type: String,
                default: 'info'
            },
            placeholder: {
                type: String,
                default: 'Type a comment'
            },
            editedLabel: {
                type: String,
                default: 'edited'
            },
            postedLabel: {
                type: String,
                default: 'edited'
            },
            paginate: {
                type: Number,
                default: 5
            }
        },
        computed: {
            filteredCommentsList() {
                if (this.queryString) {
                    return this.commentsList.filter(comment => {
                        return comment.body.toLowerCase().indexOf(this.queryString.toLowerCase()) > -1 ||
                            comment.owner.full_name.toLowerCase().indexOf(this.queryString.toLowerCase()) > -1;
                    })
                }

                return this.commentsList;
            },
            isValidComment() {

                //reject spaced strings
                if(this.commentInputValue) {
                    return this.commentInputValue.trim();
                }

                return false;
            }
        },
        data() {
            return {
                commentInputValue: null,
                commentsList: [],
                commentsCount: null,
                editedCommentIndex: null,
                taggedUsers: [],
                queryString: "",
                loading: false,
                url: window.location.href
            };
        },
        directives: {
            inputorOnFocus: {
                inserted(el, binding, vnode) {
                    $(el).atwho({
                        at: "@",
                        displayTpl: "<li id='${id}'><img src='${avatar_link}' alt='User Image' class='atwho'> ${name}</li>",
                        callbacks: {
                            remoteFilter(query, callback) {
                                axios.get('/core/comments/getUsersList/' + query).then(response => {
                                    callback(response.data.usersList);
                                });
                            }
                        }
                    });

                    $(el).on('inserted.atwho', (event, li, query) => {
                        vnode.context.taggedUsers.push({
                            'id': $(li).attr('id'),
                            'full_name': $(li).text().trim()
                        });
                    });
                }
            }
        },
        methods: {
            getData() {
                this.loading = true;

                axios.get('/core/comments/list', { params: this.getRequestParams() }).then(response => {
                    this.commentsList =  this.commentsList.concat(response.data.list);
                    this.commentsCount = response.data.count;
                    this.loading = false;
                }).catch(error => {
                    this.loading = false;

                    if (error.response.data.level) {
                        toastr[error.response.data.level](error.response.data.message);
                    }
                });
            },
            getRequestParams() {
                return {
                    id: this.id,
                    type: this.type,
                    offset: this.commentsList.length,
                    paginate: this.paginate
                };
            },
            matchTaggedUsers(body) {
                let self = this;

                this.taggedUsers.forEach(function(user, index) {
                    if (!body.includes(user.full_name)) {
                        self.taggedUsers.splice(index, 1);
                    }
                });
            },
            addComment() {
                if (!this.commentInputValue) {
                    return;
                }

                this.matchTaggedUsers(this.commentInputValue);
                let params = this.postRequestParams();
                this.commentInputValue = null;
                this.taggedUsers = [];

                axios.post('/core/comments/post', params).then((response) => {
                    this.commentsList.unshift(response.data.comment);
                    this.commentsCount = response.data.count;
                    this.loading = false;
                }).catch(error => {
                    this.loading = false;

                    if (error.response.data.level) {
                        toastr[error.response.data.level](error.response.data.message);
                    }
                });
            },
            postRequestParams() {
                return {
                    id: this.id,
                    type: this.type,
                    comment: this.commentInputValue,
                    tagged_users_list: this.taggedUsers,
                    url: this.url
                };
            },
            updateComment(comment) {
                this.matchTaggedUsers(comment.body);
                comment.is_edited = true;
                comment.tagged_users_list = this.taggedUsers;
                this.editedCommentIndex = null;
                this.taggedUsers = [];
                this.loading = true;

                axios.patch('/core/comments/update/' + comment.id, {comment: comment, url: this.url}).then(response => {
                    this.loading = false;
                }).catch(error => {
                    this.loading = false;
                    if (error.response.data.level) {
                        toastr[error.response.data.level](error.response.data.message);
                    }
                });
            },
            deleteComment(index) {
                this.loading = true;

                axios.delete('/core/comments/destroy/' + this.commentsList[index].id).then((response) => {
                    this.commentsList.splice(index,1);
                    this.commentsCount--;
                    this.loading = false;
                }).catch(error => {
                    this.loading = false;
                    if (error.response.data.level) {
                        toastr[error.response.data.level](error.response.data.message);
                    }
                });
            },
            highlightTaggedUsers(comment) {
                let body = comment.body;

                comment.tagged_users_list.forEach(user => {
                    body = body.replace('@' + user.full_name, '<span style="color: #3097d1;">' + '@' + user.full_name + '</span>');
                })

                return body;
            }
        },
        mounted() {
            this.getData();
        }
    }

</script>

<style>

    .atwho-view ul li > img {
        width: 25px;
        height: 25px;
    }

    .box-body.chat {
        overflow-y:scroll;
        max-height: 300px
    }

    p.message {
        overflow-x:hidden
    }

    textarea.comment {
        resize:vertical;
    }

</style>
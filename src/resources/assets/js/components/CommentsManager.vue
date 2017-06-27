<template>

    <div :class="'box collapsed-box box-' + headerClass">
        <div class="box-header with-border">
            <i class="fa fa-comments-o"></i>
            <h3 class="box-title">
                <slot name="comments-manager-title"></slot>
            </h3>
             <div class="box-tools pull-right">
                <i v-if="commentList.length > 1"
                    class="fa fa-search">
                </i>
                <input type="text"
                    size="15"
                    class="comments-filter margin-right-xs"
                    v-model="query"
                    v-if="commentList.length > 1">
                <span class="badge bg-orange">
                    {{ commentsCount }}
                </span>
                <button type="button"
                    class="btn btn-box-tool btn-sm"
                    @click="get()">
                    <i class="fa fa-refresh"></i>
                </button>
                <button class="btn btn-box-tool btn-sm"
                    data-widget="collapse">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="box-body chat">
            <div class="item"
                v-for="(comment, index) in filteredCommentList">
                <img :src="'/core/avatars/' + comment.owner.avatarId"
                    alt="user image"
                    class="offline">
                <p class="message">
                    <small class="pull-right margin-right-xs">
                        <span v-if="editedCommentIndex === null" :key="'buttons-' + index">
                            <span v-if="comment.is_edited">
                                <i class="fa fa-pencil-square-o"></i>
                                {{ comment.updated_at | timeFromNow }}
                            </span>
                            <span>
                                <i class="fa fa-pencil"></i>
                                {{ comment.created_at | timeFromNow }}
                            </span>
                        </span>
                        <i class="btn btn-xs btn-warning fa fa-pencil-square-o margin-right-xs"
                            :key="'edit-' + index"
                            @click="editedCommentIndex = index;taggedUsers=comment.tagged_users_list"
                            v-if="comment.is_editable && editedCommentIndex === null">
                        </i>
                        <i class="btn btn-xs btn-danger fa fa-trash-o"
                            :key="'delete-' + index"
                            @click="destroy(comment)"
                            v-if="comment.is_deletable && editedCommentIndex === null">
                        </i>
                        <i class="btn btn-xs btn-success fa fa-check"
                            @click="patch(comment)"
                            :key="'update-' + index"
                            v-if="editedCommentIndex === index && comment.body.trim()">
                        </i>
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
                <small class="comments-more"
                    @click="get()"
                    v-if="commentList.length">
                    <slot name="comments-manager-load-more"></slot>
                </small>
            </center>
        </div>
        <div class="box-footer" v-if="editedCommentIndex === null">
            <div class="input-group">
                <textarea class="form-control comment"
                    v-inputor-on-focus
                    v-model="commentInput"
                    :id="'textarea-' + _uid">
                </textarea>
                <div class="input-group-btn">
                    <button type="button"
                        @click="hasComment ? post() : null"
                        class="btn btn-success">
                        <i class="fa fa-check"
                            v-if="hasComment">
                        </i>
                        <i v-else
                            class="fa fa-ellipsis-h">
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
            paginate: {
                type: Number,
                default: 5
            }
        },

        computed: {
            filteredCommentList() {
                return this.query
                    ? this.commentList.filter(comment => {
                        return comment.body.toLowerCase().indexOf(this.query.toLowerCase()) > -1
                            || comment.owner.full_name.toLowerCase().indexOf(this.query.toLowerCase()) > -1;
                    })
                    : this.commentList;
            },
            hasComment() {
                return this.commentInput.trim();
            }
        },

        data() {
            return {
                commentInput: '',
                commentList: [],
                commentsCount: 0,
                editedCommentIndex: null,
                taggedUsers: [],
                query: "",
                loading: false,
                url: window.location.href
            };
        },

        directives: {
            inputorOnFocus: {
                inserted(el, binding, vnode) {
                    $(el).atwho({
                        at: "@",
                        searchKey: "full_name",
                        displayTpl: "<li id='${id}' name='${full_name}'><img src='/core/avatars/${avatar_id}' alt='User Image' class='atwho'> ${full_name}</li>",
                        insertTpl: "@${full_name}",
                        acceptSpaceBar: true,
                        callbacks: {
                            remoteFilter: _.debounce((query, callback) => {
                                axios.get('/core/comments/getTaggableUsers/' + query).then(response => {
                                    callback(response.data);
                                });
                            }, 200)
                        }
                    });

                    $(el).on('inserted.atwho', (event, li, query) => {
                        vnode.context.taggedUsers.push({
                            'id': $(li).attr('id'),
                            'full_name': $(li).attr('name')
                        });
                    });
                }
            },
            unbind(el, binding, vnode) {
                $(el).atwho('destroy');
            }
        },

        methods: {
            get() {
                this.loading = true;

                axios.get('/core/comments', { params: this.getParams() }).then(response => {
                    this.commentList = this.commentList.concat(response.data.list);
                    this.commentsCount = response.data.count;
                    this.loading = false;
                }).catch(error => {
                    this.loading = false;
                    this.reportEnsoException(error);
                });
            },
            getParams() {
                return {
                    id: this.id,
                    type: this.type,
                    offset: this.commentList.length,
                    paginate: this.paginate
                };
            },
            post() {
                this.loading = true;

                axios.post('/core/comments', this.postParams()).then(response => {
                    this.commentList.unshift(response.data.comment);
                    this.commentsCount = response.data.count;
                    this.loading = false;
                }).catch(error => {
                    this.loading = false;
                    this.reportEnsoException(error);
                });
            },
            postParams() {
                this.syncTaggedUsers(this.commentInput);

                let params = {
                    id: this.id,
                    type: this.type,
                    body: this.commentInput,
                    tagged_users_list: this.taggedUsers,
                    url: this.url
                };

                this.commentInput = '';
                this.taggedUsers = [];

                return params;
            },
            patch(comment) {
                let index = this.commentList.indexOf(comment);
                this.loading = true;

                axios.patch('/core/comments/' + comment.id, this.patchParams(comment)).then(response => {
                    this.loading = false;
                    this.commentList.splice(index, 1);
                    this.commentList.unshift(response.data.comment);
                }).catch(error => {
                    this.loading = false;
                    this.reportEnsoException(error);
                });
            },
            patchParams(comment) {
                this.syncTaggedUsers(comment.body);
                comment.tagged_users_list = this.taggedUsers;
                comment.url = this.url;
                this.editedCommentIndex = null;
                this.taggedUsers = [];

                return comment;
            },
            destroy(comment) {
                this.loading = true;

                axios.delete('/core/comments/' + comment.id).then(response => {
                    let index = this.commentList.indexOf(comment);
                    this.commentList.splice(index,1);
                    this.commentsCount--;
                    this.loading = false;
                }).catch(error => {
                    this.loading = false;
                    this.reportEnsoException(error);
                });
            },
            syncTaggedUsers(body) {
                let self = this;

                this.taggedUsers.forEach(function(user, index) {
                    if (!body.includes(user.full_name)) {
                        self.taggedUsers.splice(index, 1);
                    }
                });
            },
            highlightTaggedUsers(comment) {
                let body = comment.body;

                comment.tagged_users_list.forEach(user => {
                    let highlightedName = '<span style="color: #3097d1;">' + '@' + user.full_name + '</span>';
                    body = body.replace('@' + user.full_name, highlightedName);
                })

                return body;
            }
        },

        mounted() {
            this.get();
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

    .comments-more {
        cursor: pointer;
        color: #909090;
    }

</style>
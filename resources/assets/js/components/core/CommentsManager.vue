<template>

    <div :class="['box box-' + headerClass, { 'collapsed-box': collapsed }]">
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
        <div class="box-body chat"
            style="overflow-y:scroll; max-height: 300px">
            <div class="item"
                v-for="(comment, index) in filteredCommentsList">
                <img :src="comment.user.avatar_saved_name"
                    alt="user image"
                    class="offline">
                <p class="message"
                    style="overflow-x:hidden">
                <small class="text-muted pull-right">
                    <span v-show="comment.is_edited">{{ editedLabel }}
                    </span>
                    <i class="fa fa-clock-o">
                    </i> {{ timeFormat(comment.updated_at) }}
                    <i class="btn btn-xs btn-warning fa fa-pencil-square-o btn-box-tool"
                        @click="editComment(index)">
                    </i>
                    <i class="btn btn-xs btn-danger fa fa-trash-o btn-box-tool"
                        @click="deleteComment(index)">
                    </i>
                    </small>
                    <a href="#"
                        class="name">
                        {{ comment.user.first_name}} {{ comment.user.last_name }}
                    </a>
                    <span v-html="highlightTaggedUsers(comment)"></span>
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
        <div class="box-footer">
            <div class="input-group">
                <textarea class="form-control"
                       :placeholder="placeholder"
                       v-model="commentInputValue"
                       :id="'textarea-' + _uid">
               </textarea>
                <div class="input-group-btn">
                    <button type="button"
                            class="btn btn-success"
                            @click="handleCommentInput">
                    <i class="fa fa-check"
                        v-if="commentInputValue">
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
            paginate: {
                type: Number,
                default: 5
            },
            collapsed: {
                type: Boolean,
                default: true
            }
        },
        computed: {

            filteredCommentsList: function() {

                if (this.queryString) {

                    return this.commentsList.filter((comment) => {

                        return comment.body.toLowerCase().indexOf(this.queryString.toLowerCase()) > -1 ||
                            comment.user.full_name.toLowerCase().indexOf(this.queryString.toLowerCase()) > -1;
                    })
                }

                return this.commentsList;
            },
        },
        data: function() {

            return {
                commentInputValue: null,
                commentsList: [],
                commentsCount: null,
                updateCommentIndex: null,
                taggedUsers: [],
                queryString: "",
                loading: false
            };
        },
        watch: {

            'id': {
                handler: 'initComponent'
            }
        },
        methods: {

            initComponent: function() {

                this.commentsList = [];
                this.commentInputValue = null;
                this.updateCommentIndex = null;
                this.taggedUsers = [];
                this.getData();
            },
            getData: function() {

                var params = {
                    id: this.id,
                    type: this.type,
                    offset: this.commentsList.length,
                    paginate: this.paginate
                };

                this.loading = true;

                axios.get('/core/comments/list', { params: params }).then((response) => {

                    this.commentsList =  this.commentsList.concat(response.data.list);
                    this.commentsCount = response.data.count;
                    this.loading = false;
                });
            },
            handleCommentInput: function() {

                if(this.updateCommentIndex !== null) {

                    this.updateComment();
                }
                else {

                    this.addComment();
                }
            },
            checkTaggedUsers: function() {

                var self = this;

                self.taggedUsers.forEach(function(user, index) {

                    if (!self.commentInputValue.includes(user.name)) {

                        self.taggedUsers.splice(index, 1);
                    }
                });
            },
            addComment: function() {

                this.checkTaggedUsers();

                var params = {
                    id: this.id,
                    type: this.type,
                    comment: this.commentInputValue,
                    taggedUsers: this.taggedUsers
                };

                this.loading = true;

                axios.post('/core/comments/post', params).then((response) => {

                    this.commentsList.unshift(response.data.comment);
                    this.commentsCount = response.data.count;
                    this.commentInputValue = null;
                    this.taggedUsers = [];
                    this.loading = false;
                });
            },
            editComment: function(index) {

                this.updateCommentIndex = index;
                this.commentInputValue = this.commentsList[this.updateCommentIndex].body;
                var self = this;

                this.commentsList[this.updateCommentIndex].tagged_users.forEach(function(user) {

                    self.taggedUsers.push({ id: user.id, name: user.full_name });
                })
            },
            updateComment: function() {

                this.checkTaggedUsers();

                var commentId = this.commentsList[this.updateCommentIndex].id,
                    params = {
                        comment: this.commentInputValue,
                        taggedUsers: this.taggedUsers
                    };

                this.loading = true;

                axios.patch('/core/comments/update/' + commentId , params).then((response) => {

                    this.commentsList[this.updateCommentIndex] = response.data;
                    this.commentInputValue = null;
                    this.updateCommentIndex = null;
                    this.taggedUsers = [];
                    this.loading = false;
                });
            },
            deleteComment: function(index) {

                this.loading = true;

                axios.delete('/core/comments/destroy/' + this.commentsList[index].id).then((response) => {

                    this.commentsList.splice(index,1);
                    this.commentsCount--;
                    this.loading = false;
                });
            },
            timeFormat: function(time) {

                return moment(time).fromNow();
            },
            highlightTaggedUsers: function(comment) {

                var body = comment.body;

                comment.tagged_users.forEach(function(user) {

                    body = body.replace('@' + user.full_name, '<span style="color: #3097d1;">' + '@' + user.full_name + '</span>');
                })

                return body;
            }
        },
        mounted: function() {
            var self = this;

            var inputor = $('#textarea-' + this._uid).atwho({
                at: "@",
                displayTpl: "<li id='${id}'><img src='${avatar}' alt='User Image' class='atwho'> ${name}</li>",
                callbacks: {
                    remoteFilter: function(query, callback) {
                        axios.get('/core/comments/getUsersList/' + query).then((response) => {

                            callback(response.data.usersList);
                        });
                    }
                }
            });

            inputor.on("inserted.atwho", function(event, li, query) {

                self.taggedUsers.push({

                    'id': $(li).attr('id'),
                    'name': $(li).text().trim()
                });

                self.commentInputValue = $('#textarea-' + self._uid).val();
            });

            this.initComponent();
        }
    }

</script>

<style>

    .atwho-view ul li > img {
        width: 25px;
        height: 25px;
    }

</style>
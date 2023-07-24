import {Controller} from '@hotwired/stimulus';
import axios from "axios";
import {getLang} from "./helpers";

export default class extends Controller {

    static values = {
        blogPostId: String,
        blogPostSlug: String,
    }

    upvote(event) {
        axios.post(`/posts/${this.blogPostIdValue}/upvote`).then(
            () => window.location.href = "/" + getLang() + `/posts/${this.blogPostSlugValue}`);
    }

    downvote(event) {
        axios.post(`/posts/${this.blogPostIdValue}/downvote`).then(
            () => window.location.href = "/" + getLang() + `/posts/${this.blogPostSlugValue}`);
    }

    favorite(event) {
        axios.post(`/posts/${this.blogPostIdValue}/favorite`).then(
            () => window.location.href = "/" + getLang() + `/posts/${this.blogPostSlugValue}`);
    }

    unfavorite(event) {
        axios.post(`/posts/${this.blogPostIdValue}/unfavorite`).then(
            () => window.location.href = "/" + getLang() + `/posts/${this.blogPostSlugValue}`);
    }

    deleteBlogPost() {
        axios.delete(`/posts/${this.blogPostIdValue}/delete`).then(
            () => window.location.href = "/" + getLang());
    }


}

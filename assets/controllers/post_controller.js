import { Controller } from '@hotwired/stimulus';
import axios from "axios";

export default class extends Controller {

    static values={
        blogPostId:String,
    }
    upvote(event) {
        event.preventDefault();
        axios.post(`/posts/${this.blogPostIdValue}/upvote`);
    }

    favorite(event) {
        axios.post(`/posts/${this.blogPostIdValue}/favorite`);
    }
    unfavorite(event) {
        axios.post(`/posts/${this.blogPostIdValue}/unfavorite`);
    }
}

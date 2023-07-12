import {Controller} from '@hotwired/stimulus';
import axios from "axios";

export default class extends Controller {

    static values = {
        blogPostId: String,
    }

    deleteBlogPost() {
        axios.delete(`/posts/${this.blogPostIdValue}/delete`).then(
            () => window.location.href = "/");
    }
}

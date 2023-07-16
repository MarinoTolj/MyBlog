import {Controller} from '@hotwired/stimulus';
import axios from "axios";
import {getLang} from "./helpers";

export default class extends Controller {

    static values = {
        blogPostId: String,
    }

    deleteComment(event) {

        let commentId = event.target.dataset.commentId
        axios.delete(`/comments/${commentId}/delete`).then(
            () => window.location.href = "/" + getLang() + `/posts/${this.blogPostIdValue}`);
    }
}

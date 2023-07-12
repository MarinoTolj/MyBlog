import {Controller} from '@hotwired/stimulus';
import axios from "axios";

export default class extends Controller {

    static values = {
        commentId: String,
    }

    deleteComment() {
        axios.delete(`/comments/${this.commentIdValue}/delete`);
    }
}

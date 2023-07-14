import {Controller} from '@hotwired/stimulus';
import axios from "axios";

export default class extends Controller {
    deleteComment(event) {

        let commentId = event.target.dataset.commentId
        axios.delete(`/comments/${commentId}/delete`);
    }
}

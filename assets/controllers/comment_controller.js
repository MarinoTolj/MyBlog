import {Controller} from '@hotwired/stimulus';
import axios from "axios";

export default class extends Controller {

    // static values = {
    //     commentId: String,
    // }

    deleteComment() {
        let commentParent = document.querySelector(".comment")
        let commentId = commentParent.dataset.commentId
        axios.delete(`/comments/${commentId}/delete`);
    }
}

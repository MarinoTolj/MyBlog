import {Controller} from '@hotwired/stimulus';
import axios from "axios";

export default class extends Controller {

    static values = {
        categoryId: String,
    }

    deleteCategory() {
        axios.delete(`/categories/category/${this.categoryIdValue}/delete`).then(() => window.location.href = "/");
    }
}

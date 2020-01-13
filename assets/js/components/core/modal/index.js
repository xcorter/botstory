class Modal {

    show(formElement) {
        formElement.style.display = "block";
        const closeElements = this.getCloseElements(formElement);
        for (const el of closeElements) {
            el.addEventListener('click', this.closeHandler.bind(this))
        }
    }

    closeHandler(event) {
        const formElement = event.target.closest('.modal');
        this.close(formElement);
    }

    close(formElement) {
        formElement.style.display = "none";
        const closeElements = this.getCloseElements(formElement);
        for (const el of closeElements) {
            el.removeEventListener('click', this.close);
        }
    }

    getCloseElements(formElement) {
        return formElement.getElementsByClassName('action-close');
    }
}

export default new Modal();
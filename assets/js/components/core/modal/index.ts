class Modal {

    show(formElement: HTMLElement ) {
        formElement.style.display = "block";
        const closeElements = this.getCloseElements(formElement);
        Array.from(closeElements).map(closeElement => {
            closeElement.addEventListener('click', this.closeHandler.bind(this));
        });
    }

    closeHandler(event: MouseEvent) {
        const formElement = (<HTMLElement>event.target).closest('.modal') as HTMLElement;
        this.close(formElement);
    }

    close(formElement: HTMLElement ) {
        formElement.style.display = "none";
        const closeElements = this.getCloseElements(formElement);
        Array.from(closeElements).map(closeElement => {
            closeElement.removeEventListener('click', this.closeHandler);
        });
    }

    getCloseElements(formElement: HTMLElement): HTMLCollectionOf<Element> {
        return formElement.getElementsByClassName('action-close');
    }
}

export default new Modal();
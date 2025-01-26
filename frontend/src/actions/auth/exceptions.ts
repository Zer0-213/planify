export class FormValidationException extends Error {
    constructor(message: string) {
        super('FormValidation');
        this.message = message;
    }
}
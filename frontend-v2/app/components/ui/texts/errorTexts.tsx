type Props = {
    text: string
}

const ErrorText = ({text}: Props) => {
    return (
        <p className="text-red-500 text-sm text-center">
            {text}
        </p>
    );
}

export default ErrorText;
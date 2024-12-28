namespace WebApplication1.Utils.ResponseObjects;

public enum ErrorCode
{
    NoAccess = 0,
    InvalidCredentials = 1,
    NoCompany = 2,
    SessionIdError = 3
}

public static class ErrorCodeExtensions
{
    public static string ToErrorCodeString(this ErrorCode errorCode)
    {
        return errorCode switch
        {
            ErrorCode.InvalidCredentials => "InvalidCredentials",
            ErrorCode.NoCompany => "NoCompany",
            ErrorCode.SessionIdError => "NoSession",
            _ => "NoAccess"
        };
    }
}

public class UnauthorizedResponse(ErrorCode errorCode = ErrorCode.NoAccess)
{
    public string Message { get; set; } = "Unauthorized access";
    public string ErrorCode { get; set; } = errorCode.ToErrorCodeString();
    public string Description { get; set; } = "You do not have permission to access this resource.";
    public DateTime Timestamp { get; set; } = DateTime.UtcNow;
}
namespace WebApplication1.Common.DTOs;

public class ErrorDto
{
    public required int Code { get; set; }
    public required string Message { get; set; }

    public string Description { get; set; } = "";
}
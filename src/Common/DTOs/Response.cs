namespace WebApplication1.Common.DTOs;

public class Response<T>
{
    public bool Success { get; set; }
    public ErrorDto? Error { get; set; }

    public T? Data { get; set; }
}
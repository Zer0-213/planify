namespace WebApplication1.Authentication.DTOs;

public class SessionDto
{
    public int Id { get; set; }
    public string Token { get; set; } = null!;
    public DateTime ExpiresAt { get; set; }
}
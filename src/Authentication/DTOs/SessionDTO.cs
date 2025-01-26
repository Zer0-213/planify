namespace WebApplication1.Authentication.DTOs;

public class SessionDto
{
    public int UserId { get; set; }
    public string Token { get; set; } = null!;
    public DateTime ExpiresAt { get; set; }
    public int? CompanyId { get; set; }
}
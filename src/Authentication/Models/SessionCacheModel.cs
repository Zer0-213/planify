namespace WebApplication1.Authentication.Models;

public class SessionCacheModel
{
        public int UserId { get; set; }
        public int? CompanyId { get; set; }
        public DateTime ExpiresAt { get; set; }
        public required string Token { get; set; }
}
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using WebApplication1.User.Models;

namespace WebApplication1.Authentication.Models;

[Table("sessions")]
public class SessionModel
{
    [Column("id")] public int Id { get; set; }

    [Column("created_at")] public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

    [Column("token_hash")]
    [MaxLength(255)]
    public string TokenHash { get; set; } = null!;

    [Column("user_id")] public int UserId { get; set; }

    [Column("expires_at")] public DateTime ExpiresAt { get; set; }

    [Column("last_active_at")] public DateTime LastActiveAt { get; set; } = DateTime.UtcNow;

    public UserModel User { get; set; } = null!;
}
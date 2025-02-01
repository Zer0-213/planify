using System.ComponentModel.DataAnnotations.Schema;
using WebApplication1.Roles;
using WebApplication1.User;
using WebApplication1.User.Models;

namespace WebApplication1.UserRole.Models;

[Table("user_roles")]
public class UserRoleModel
{
    [Column("id")] public int Id { get; set; }

    [Column("created_at")] public DateTime CreatedAt { get; set; }

    [Column("updated_at")] public DateTime UpdatedAt { get; set; }

    [Column("user_id")] public int UserId { get; set; }

    public UserModel User { get; set; } = null!;


    [Column("role_id")] public int RoleId { get; set; }

    public RolesModel Role { get; set; } = null!;
}
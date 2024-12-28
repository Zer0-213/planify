using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using WebApplication1.Permission;

namespace WebApplication1.Roles;

[Table("roles")]
public class RolesModel
{
    [Key] [Column("id")] public int Id { get; set; }

    [Column("created_at")] public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

    [Column("name")]
    [MaxLength(50)]
    [Required]
    public string Name { get; set; } = null!;

    [Column("description")]
    [MaxLength(100)]
    public string Description { get; set; } = null!;

    [Column("permission_id")] [Required] public int PermissionId { get; set; }

    public PermissionModel Permission { get; set; } = null!;
}
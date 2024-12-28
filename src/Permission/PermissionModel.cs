using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace WebApplication1.Permission;

[Table("permissions")]
public class PermissionModel
{
    [Column("id")] public int Id { get; set; }

    [Column("name")] [MaxLength(25)] public string Name { get; set; } = null!;

    [Column("description")] public string Description { get; set; } = null!;
}
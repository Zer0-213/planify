using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace WebApplication1.Company.Models;

[Table("companies")]
public class CompanyModel
{
    [Column("id")] public int Id { get; set; }

    [Column("created_at")] public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

    [Column("name")]
    [MaxLength(50)]
    [Required]
    public string Name { get; set; } = "";


    [Column("phone_number")]
    [MaxLength(15)]
    public string PhoneNumber { get; set; } = "";

    [Column("type")] public int Type { get; set; }
}
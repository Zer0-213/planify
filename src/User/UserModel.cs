using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using WebApplication1.Company.Models;

namespace WebApplication1.User;

[Table("users")]
public class UserModel
{
    [Column("id")] public int Id { get; set; }

    [Column("first_name")]
    [MaxLength(25)]
    [Required]
    public string FirstName { get; set; } = "";

    [Column("last_name")]
    [MaxLength(25)]
    [Required]
    public string LastName { get; set; } = "";

    [Column("created_at")] public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

    [Column("email")]
    [MaxLength(50)]
    [Required]
    public string Email { get; set; } = "";

    [Column("password_hashed")]
    [MaxLength(256)]
    [Required]
    public string PasswordHashed { get; set; } = "";

    [Column("date_of_birth")] public DateTime? DateOfBirth { get; set; }

    [Column("company_id")] public int? CompanyId { get; set; }

    public CompanyModel Company { get; set; } = null!;
}
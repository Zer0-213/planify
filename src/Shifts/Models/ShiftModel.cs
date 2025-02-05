using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using Microsoft.EntityFrameworkCore;
using WebApplication1.Company.Models;
using WebApplication1.User.Models;

namespace WebApplication1.Shifts.Models;

[Table("shifts")]
[Index (nameof(UserId))]
[Index(nameof(CompanyId))]
public class ShiftModel
{
    [Column("id")] public int Id { get; set; }
    
    [Column("created_at")] public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
    
    [Column("date")] 
    [Required]
    public DateOnly Date { get; set; }
    
    [Column("start_time")]
    [Required]
    public TimeSpan StartTime { get; set; }
    
    [Column("end_time")]
    [Required]
    public TimeSpan EndTime { get; set; }
    
    [Column("user_id")]
    public int UserId { get; set; }
    
    public UserModel User { get; set; } = null!;
 
    
    [Column("company_id")]
    public int CompanyId { get; set; }
    
    public CompanyModel Company { get; set; } = null!;
}
using System.ComponentModel.DataAnnotations;

namespace WebApplication1.Company.DTOs;

public class CreateCompanyDto
{
    public int UserId { get; set; }
    [Required] public string CompanyName { get; set; } = "";

    [Required] public string CompanyPhone { get; set; } = "";

    [Required] public int CompanyType { get; set; }
}
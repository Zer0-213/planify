namespace WebApplication1.Authentication.DTOs;

public class RegisterDto
{
    public int? Id { get; set; }
    public DateTime? CreatedAt { get; set; }
    public required string Email { get; set; }
    public required string Password { get; set; }
    public required string FirstName { get; set; }
    public required string LastName { get; set; }
    public required DateTime? DateOfBirth { get; set; }
}
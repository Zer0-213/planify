namespace WebApplication1.Shifts.DTOs;


public class ShiftDto()
{
    
    public int UserId { get; set; }
    public string UserName { get; set; } = string.Empty;
    
    public string? Monday { get; set; }
    public string? Tuesday { get; set; }
    public string? Wednesday { get; set; }
    public string? Thursday { get; set; }
    public string? Friday { get; set; }
    public string? Saturday { get; set; }
    public string? Sunday { get; set; }
}
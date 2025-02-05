using WebApplication1.Shifts.Repositories;
using WebApplication1.Shifts.DTOs;
using WebApplication1.Shifts.Models;

namespace WebApplication1.Shifts.Services;

public interface IShiftService
{
    ShiftResponseDto GetShiftsByUserId(int userId, DateOnly startDate, DateOnly endDate);
}

public class ShiftService(IShiftRepository shiftRepository) : IShiftService
{
    public ShiftResponseDto GetShiftsByUserId(int userId, DateOnly startDate, DateOnly endDate)
    {
        var shifts = shiftRepository.QueryShiftsByCompanyId(userId, startDate, endDate);

        var shiftGroups = shifts
            .GroupBy(s => new { s.UserId, s.User.FirstName, s.User.LastName })
            .Select(group => new ShiftDto
            {
                UserId = group.Key.UserId,
                UserName = group.Key.FirstName + " " + group.Key.LastName,
                Monday = FormatShift(group.FirstOrDefault(s => s.Date.DayOfWeek == DayOfWeek.Monday)),
                Tuesday = FormatShift(group.FirstOrDefault(s => s.Date.DayOfWeek == DayOfWeek.Tuesday)),
                Wednesday = FormatShift(group.FirstOrDefault(s => s.Date.DayOfWeek == DayOfWeek.Wednesday)),
                Thursday = FormatShift(group.FirstOrDefault(s => s.Date.DayOfWeek == DayOfWeek.Thursday)),
                Friday = FormatShift(group.FirstOrDefault(s => s.Date.DayOfWeek == DayOfWeek.Friday)),
                Saturday = FormatShift(group.FirstOrDefault(s => s.Date.DayOfWeek == DayOfWeek.Saturday)),
                Sunday = FormatShift(group.FirstOrDefault(s => s.Date.DayOfWeek == DayOfWeek.Sunday))
            })
            .ToList();

        return new ShiftResponseDto
        {
            Shifts = shiftGroups
        };
    }

    private static string? FormatShift(ShiftModel? shift)
    {
        return shift != null ? $"{shift.StartTime:hh\\:mm} - {shift.EndTime:hh\\:mm}" : "OFF";
    }
}
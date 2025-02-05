using Microsoft.AspNetCore.Mvc;
using WebApplication1.Common.DTOs;
using WebApplication1.Shifts.Services;
using WebApplication1.Utils.Middleware;

namespace WebApplication1.Shifts.Controllers;

[ApiController]
[Route("api/shifts")]
public class ShiftsController(IShiftService shiftService):ControllerBase
{
    
    [HttpGet("get-shifts")]
    [TypeFilter(typeof(UserAuthFilter))]
    public ActionResult GetShifts()
    {
        try
        {
            HttpContext.Request.Headers.TryGetValue("companyId", out var companyIdStr);
            if (!int.TryParse(companyIdStr, out var companyId))
            {
                return BadRequest(new ErrorDto
                {
                    Code = 400,
                    Message = "Invalid companyId",
                    Description = "Invalid companyId"
                });
            }
             
            var dateFrom = HttpContext.Request.Query["dateFrom"];
            var dateTo = HttpContext.Request.Query["dateTo"];
            
            if (!DateOnly.TryParse(dateFrom, out var dateFromParsed) || !DateOnly.TryParse(dateTo, out var dateToParsed))
            {
                return BadRequest(new ErrorDto
                {
                    Code = 400,
                    Message = "Invalid date",
                    Description = "Invalid date"
                });
            }
            
            var data = shiftService.GetShiftsByUserId(companyId, dateFromParsed, dateToParsed);
            
            return Ok(data);
        }
        catch (Exception e)
        {
            Console.WriteLine(e);
            throw;
        }
    }
    
}
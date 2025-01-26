using Microsoft.AspNetCore.Mvc;
using WebApplication1.Common.DTOs;
using WebApplication1.Company.DTOs;

namespace WebApplication1.Company.Controllers;

public interface ICompanyController
{
    ActionResult<ErrResponse> CreateCompany(CreateCompanyDto createCompany);
}
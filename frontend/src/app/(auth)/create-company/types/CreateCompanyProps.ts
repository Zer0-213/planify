import {GetUserDTO} from "../../../../dtos/getUserDTO";
import {ErrorDTO} from "../../../../utils/dtos/errorDTO";

export type CreateCompanyProps = {
    user?: GetUserDTO,
    error?: ErrorDTO
}
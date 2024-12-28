type ResponseDTO<T> = {
  success: boolean;
  data: T | null;
  error: ErrorDTO | null;
};

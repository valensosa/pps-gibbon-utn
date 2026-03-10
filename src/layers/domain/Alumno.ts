interface Alumno {
  dni: string;
  nombre: string;
  apellido: string;
  email: string;
  estado: string;

  getDni(): string;
  getNombre(): string;
  getApellido(): string;
  getEmail(): string;
  getEstado(): string;

  setDni(dni: string): void;
  setNombre(nombre: string): void;
  setApellido(apellido: string): void;
  setEmail(email: string): void;
  setEstado(estado: string): void;

  validarDni(): boolean;
}

export { Alumno };

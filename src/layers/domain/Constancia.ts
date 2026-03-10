interface Constancia {
  dniAlumno: number;
  materiaId: number;
  fechaExcamen: Date;
  estado: string;

  getFechaExcamen(): Date;
  cambiarEstado(): void;
  esValida(): boolean;
}

export { Constancia };

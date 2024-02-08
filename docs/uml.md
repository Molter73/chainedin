@startuml
left to right direction

actor Reclutador as r

usecase "Crear usuario" as UC1
usecase "Crear nueva vacante" as UC2
usecase "Consultar candidatos para vacante" as UC3
usecase "Visualizar perfil de candidato" as UC4
usecase "Visualizar perfil propio" as UC5
usecase "Editar perfil propio" as UC6

r --> UC1
r --> UC2
r --> UC3
r --> UC4
r --> UC5
r --> UC6
@enduml

@startuml
left to right direction

actor Usuario as u

usecase "Crear usuario" as UC1
usecase "Obtener lista de vacantes" as UC2
usecase "Postularse a vacante" as UC3
usecase "Listar empleos postulado" as UC4
usecase "Visualizar perfil" as UC5
usecase "Editar perfil" as UC6

u --> UC1
u --> UC2
u --> UC3
u --> UC4
u --> UC5
u --> UC6
@enduml

@startuml
Frontend --> API: GET /profile.php?id=1
API --> MySQL: SELECT ... WHERE id=1;
MySQL --> API: profile row
API --> Frontend: {"data":{"name":...
@enduml

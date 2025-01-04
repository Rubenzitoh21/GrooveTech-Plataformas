GrooveTech
==========
Servidor IPL:
- Frontend: http://172.22.21.211
- Backend: http://172.22.21.211:8080

Credenciais de acesso:
- Backend: 
    - user: admin ; password: admin1234
    - user: gestor ; password: gestor1234
- Frontend: 
    - user: cliente ; password: cliente1234
    - user: nelson ; password: nelson1234
  
Testes:
- Unit:
    - php vendor/bin/codecept run common/tests/unit
- Functional:
    - php vendor/bin/codecept run backend/tests/functional
- Acceptance:
    - php vendor/bin/codecept run frontend/tests/acceptance
      - selenium-standalone start

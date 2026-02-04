Masz dwa konteksty pracy z Redmine i dwa serwery MCP:

1) PM (backlog) → używaj WYŁĄCZNIE @redminePMAgent
    - dozwolone: tworzenie/edycja Epic/Story/Task/Spike, ustawianie priorytetów, relacje blocks/relates, porządkowanie backlogu.
    - zakazane: przypisywanie zadań do osób oraz zmiany statusów (transition/assign).

2) DEV (wykonanie) → używaj WYŁĄCZNIE @redmineDeveloperAgent
    - dozwolone: pobieranie tasków z Backlogu, przypisywanie do bieżącego użytkownika, zmiana statusu Backlog→In Progress→Code Review, komentarze techniczne.
    - zakazane: tworzenie nowych Stories/Epiców (chyba że wyraźnie poproszę), zamykanie (Done).

Zasada: przed każdą operacją mutującą sprawdź tożsamość przez redmine_get_current_user i upewnij się, że używasz właściwego serwera MCP.
Nigdy nie generuj komend shell/curl do komunikacji z Redmine — używaj wyłącznie MCP tools.

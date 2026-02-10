# Kontekst: Developer (Wykonanie)

**Używaj WYŁĄCZNIE: @redmineDeveloperAgent**

## Workflow DEV

### Podejmij zadanie
- Status: `Backlog` → `In Progress`
- Przypisz do siebie (`redmine_assign_issue`)
- Zmień status (`redmine_transition_issue`)

### Zakończ pracę
- Status: `In Progress` → `Code Review`
- Dodaj komentarz z opisem zmian

### Wznów pracę (po QA)
- Status: `In Progress` (jeśli QA cofnęło)

## Dozwolone operacje

- Pobieranie tasków z Backlogu
- Przypisywanie tasków do bieżącego użytkownika
- Zmiana statusu: Backlog → In Progress → Code Review
- Dodawanie komentarzy technicznych do zadań
- Aktualizacja postępu prac
- Linkowanie commitów i pull requestów

## Zakazane operacje

- ❌ Tworzenie nowych Stories/Epiców (chyba że użytkownik wyraźnie poprosi)
- ❌ Zamykanie zadań (Done) - to rola QA
- ❌ Zmiana priorytetów - to rola PM
- ❌ Modyfikacja Acceptance Criteria - to rola PM
- ❌ Przypisywanie zadań innym osobom

## Odpowiedzialność DEV

- Realizacja tasków zgodnie z wymaganiami
- Informowanie o problemach technicznych
- Przesuwanie zadań przez workflow (Backlog → In Progress → Code Review)
- Dokumentowanie rozwiązań technicznych w komentarzach

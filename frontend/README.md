# React + Vite

This template provides a minimal setup to get React working in Vite with HMR and some ESLint rules.

Currently, two official plugins are available:

- [@vitejs/plugin-react](https://github.com/vitejs/vite-plugin-react/blob/main/packages/plugin-react) uses [Babel](https://babeljs.io/) (or [oxc](https://oxc.rs) when used in [rolldown-vite](https://vite.dev/guide/rolldown)) for Fast Refresh
- [@vitejs/plugin-react-swc](https://github.com/vitejs/vite-plugin-react/blob/main/packages/plugin-react-swc) uses [SWC](https://swc.rs/) for Fast Refresh

## React Compiler

The React Compiler is not enabled on this template because of its impact on dev & build performances. To add it, see [this documentation](https://react.dev/learn/react-compiler/installation).

## Expanding the ESLint configuration

If you are developing a production application, we recommend using TypeScript with type-aware lint rules enabled. Check out the [TS template](https://github.com/vitejs/vite/tree/main/packages/create-vite/template-react-ts) for information on how to integrate TypeScript and [`typescript-eslint`](https://typescript-eslint.io) in your project.

## Struktura projektu

```
frontend/src/
├── components/
│   ├── ui/              # Bazowe komponenty UI (Button, Input, Card, Modal)
│   ├── forms/           # Komponenty formularzy
│   └── layout/          # Layout komponenty (Header, Nav, Footer)
├── styles/
│   ├── base/
│   │   ├── _reset.scss       # Reset stylów
│   │   ├── _typography.scss  # Typografia
│   │   └── _variables.scss   # Zmienne globalne
│   ├── components/
│   │   ├── _button.scss      # Style dla Button
│   │   ├── _input.scss       # Style dla Input
│   │   ├── _card.scss        # Style dla Card
│   │   └── _modal.scss       # Style dla Modal
│   ├── layout/
│   │   ├── _header.scss      # Style dla Header
│   │   ├── _nav.scss         # Style dla nawigacji
│   │   └── _main.scss        # Style dla głównego kontenera
│   └── main.scss             # Import wszystkich stylów
├── pages/               # Strony (tylko kompozycja)
├── api/                 # Axios config
└── context/             # React Context
```

### Konwencje stylowania

- Używamy DaisyUI jako podstawowego systemu designu
- SCSS dla customowych stylów
- Tailwind utilities dla szybkich modyfikacji
- Komponenty UI w `components/ui/` są reużywalne


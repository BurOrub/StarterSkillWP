# Starter Theme Skill

**English** · [Italiano](#italiano)

A [Claude](https://claude.com) skill for working on [Starter Theme](https://github.com/BurOrub/StarterThemeWP), the classic WordPress theme with ACF blocks and a *Customize → Options* panel. Once installed, Claude knows how the theme is built, where to work and which rules not to break, so you do not have to explain it at the start of every session.

The skill loads by itself whenever you work on a project based on the theme, or mention things like `starter.css`, `template-parts`, the theme's "Opzioni", or blocks such as `hero-slider`. The skill content is written in Italian.

## What it covers

| File | Content |
|---|---|
| `SKILL.md` | Overview: how the theme is organized, where to work, the rules not to break |
| `references/architettura.md` | Map of the theme's files, key functions, CSS buckets, CSS variables |
| `references/opzioni.md` | Structure of the Customizer and how to add an option |
| `references/blocchi.md` | Available ACF blocks and fields, how to create a new one, SEO rules for headings |
| `references/regole-e-css.md` | Style rules (fonts, colors, animations, responsive) and CSS pitfalls already met |
| `references/contenuti-shop-form.md` | Sample pages, menus, WooCommerce, Contact Form 7, Flamingo, CookieYes |
| `references/portfolio.md` | Portfolio module: project page, related projects, projects list block, page transitions and loading, Clone, Starter Live |
| `references/test-build-deploy.md` | Build, local testing, editor check, going online and syncing |

## Installation

1. Download `starter-theme-skill.zip` from the latest [release](https://github.com/BurOrub/StarterSkillWP/releases/latest).
2. Install it:
   - **Claude Code**: unzip it so that the folder `starter-theme/` (the one containing `SKILL.md`) sits in `~/.claude/skills/` for all your projects, or in `.claude/skills/` inside one project.
   - **Claude (web/desktop app)**: upload the zip in the Skills section of the settings.
3. Start a new session: the skill is picked up automatically.

## Updating

Download the zip of the new release and replace the `starter-theme/` folder.

## Related repositories

- [StarterThemeWP](https://github.com/BurOrub/StarterThemeWP): the theme
- [StarterChildWP](https://github.com/BurOrub/StarterChildWP): the child theme
- [StarterLiveWP](https://github.com/BurOrub/StarterLiveWP) and [StarterSecurityWP](https://github.com/BurOrub/StarterSecurityWP): the plugins

## Publishing a version (maintainers)

Commit, then `git tag vX.Y.Z` and `git push origin main vX.Y.Z`. The workflow in `.github/workflows/release.yml` builds `starter-theme-skill.zip` and attaches it to the release.

---

## Italiano

Una skill di [Claude](https://claude.com) per lavorare su [Starter Theme](https://github.com/BurOrub/StarterThemeWP), il tema WordPress classico con blocchi ACF e pannello *Personalizza → Opzioni*. Una volta installata, Claude sa com'è fatto il tema, dove si lavora e quali regole non rompere, senza doverlo spiegare a ogni sessione.

La skill si carica da sola quando lavori su un progetto basato sul tema, o nomini cose come `starter.css`, `template-parts`, le "Opzioni" del tema o blocchi come `hero-slider`.

### Cosa contiene

| File | Contenuto |
|---|---|
| `SKILL.md` | Panoramica: come è organizzato il tema, dove si lavora, le regole da non rompere |
| `references/architettura.md` | Mappa dei file del tema, funzioni chiave, bucket CSS, variabili CSS |
| `references/opzioni.md` | Struttura del Customizer e come aggiungere un'opzione |
| `references/blocchi.md` | Blocchi ACF disponibili, campi, come crearne uno nuovo, regole SEO dei titoli |
| `references/regole-e-css.md` | Regole di stile (font, colori, animazioni, responsive), trappole CSS già incontrate |
| `references/contenuti-shop-form.md` | Pagine di esempio, menu, WooCommerce, Contact Form 7, Flamingo, CookieYes |
| `references/portfolio.md` | Modulo Portfolio: scheda progetto, correlati, blocco Elenco progetti, transizioni e caricamento, Clona, Starter Live |
| `references/test-build-deploy.md` | Build, test in locale, controllo dell'editor, messa online e sincronizzazione |

### Installazione

1. Scarica `starter-theme-skill.zip` dall'ultima [release](https://github.com/BurOrub/StarterSkillWP/releases/latest).
2. Installala:
   - **Claude Code**: decomprimila in modo che la cartella `starter-theme/` (quella con dentro `SKILL.md`) stia in `~/.claude/skills/` per tutti i progetti, oppure in `.claude/skills/` dentro un singolo progetto.
   - **Claude (app web/desktop)**: carica lo zip nella sezione Skills delle impostazioni.
3. Avvia una nuova sessione: la skill viene riconosciuta da sola.

### Aggiornamento

Scarica lo zip della nuova release e sostituisci la cartella `starter-theme/`.

### Repository collegati

- [StarterThemeWP](https://github.com/BurOrub/StarterThemeWP): il tema
- [StarterChildWP](https://github.com/BurOrub/StarterChildWP): il tema figlio
- [StarterLiveWP](https://github.com/BurOrub/StarterLiveWP) e [StarterSecurityWP](https://github.com/BurOrub/StarterSecurityWP): i plugin

### Pubblicare una versione (per chi mantiene il progetto)

Commit, poi `git tag vX.Y.Z` e `git push origin main vX.Y.Z`. L'automazione in `.github/workflows/release.yml` crea `starter-theme-skill.zip` e lo allega alla release.

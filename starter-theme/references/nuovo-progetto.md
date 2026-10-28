# Nuovo progetto: intervista e configurazione delle opzioni del tema

Si usa **solo all'inizio di un sito nuovo** (tema appena installato, opzioni di fabbrica). Su un sito già avviato non si lancia: si cambia solo ciò che l'utente chiede.
Scopo: una persona che **non conosce il tema** risponde a domande sul brand e sul sito, e le opzioni di *Aspetto → Personalizza → Opzioni* vengono popolate al posto suo, così il sito ha già header, footer, colori, font e moduli pronti.
Si lavora **in locale**; non si carica niente online (serve un ordine esplicito dell'utente).

## Chi fa cosa

- **La skill configura le opzioni del tema** (tutte quelle del Customizer che dipendono da brand e scelte di stile) e **scarica i Google Fonts**.
- **L'utente carica i contenuti**: logo, foto, video, testi e struttura delle pagine, menu, moduli di contatto, codici incollati. Non si chiedono e non si caricano. Resta sempre la risposta **"lo carico/faccio io"**: vale per ogni domanda.
- I testi che sono *opzioni* (testo del pulsante dell'header, motto, titoli del footer, copyright) si chiedono, ma con la stessa uscita di sicurezza "lo scrivo io dopo".

## Come si svolge

1. **Conferma che è un progetto nuovo** e dove si lavora (cartella del tema, URL locale, WP-CLI: vedi `test-build-deploy.md`).
2. **Consiglia la style guide**, in apertura, con parole tue: *"Se hai già un brand, i risultati migliori arrivano da una style guide. Puoi crearla con Claude Design (o lo strumento che preferisci) e darmi il link oppure il file HTML (anche sul Desktop). Se non ce l'hai, ti faccio io le domande su colori, font e pesi."* Le tre strade: **link**, **file HTML**, **nessuna** (si chiede tutto).
3. **Leggi la style guide** (se c'è) e ricava una **proposta**, senza applicare niente (vedi sotto). Mostrala in tabella (valore trovato → opzione del tema) e fai correggere.
4. **Domande a gruppi**: una tornata alla volta, 3–6 domande, risposte chiuse numerate + sempre **"altro: scrivi tu"** e **"lo faccio io dopo"**. Non rifare domande a cui la style guide, il brief o le risposte precedenti già rispondono: confermale in una riga.
5. **Profilo JSON** con tutte le risposte → `scripts/applica-profilo.php`: prima `prova` (resoconto, niente scritto), poi l'applicazione dopo l'ok dell'utente. Lo script scarica anche i Google Fonts scelti.
6. **Controllo**: le pagine principali rispondono (200), nessun errore PHP, screenshot a 1280 e 390 px della home e di una pagina interna.
7. **Riepilogo** all'utente: cosa è stato impostato, cosa lo script ha rifiutato (e perché), cosa resta da fare a lui.

## Dalla style guide alle opzioni

La style guide fatta con Claude Design è un HTML diviso per sezioni (colori, testi, font…): leggi **ogni sezione** e mappa così.

| Nella style guide | Opzione del tema |
|---|---|
| Palette: sfondo, testo, primario, secondario, accento (+ eventuali altri) | tavolozza (`palette` nel profilo, **nell'ordine 1 Sfondo, 2 Testo, 3 Primario, 4 Secondario, 5 Accento, 6…**, fino a 10), poi le scelte che li usano (`starter_color_*` con valori `slot_N`) |
| Colore dei link, dei pulsanti (pieno/contorno, hover), del footer | `starter_color_link*`, `starter_color_btn_primary*`, `starter_color_btn_secondary*`, `starter_color_footer_*` |
| Font dei titoli e del testo | `starter_font1_family` (titoli), `starter_font2_family` (testo); formato `google:Nome` o `system:Nome`; dove si applicano: `starter_font1_h1`…`h6`, `starter_font1_body`, e lo stesso per `starter_font2_*` |
| Scala dei testi: dimensioni di display, H1–H6, corpo, eyebrow | `starter_text_style_<livello>_font_size_desktop/_tablet/_mobile` (livelli `display`, `h1`…`h6`, `txt` (paragrafo), `size_xx_large`…`size_small` (classi `has-*-font-size`), `btn` (pulsanti); valori liberi in `rem`/`px`/`clamp()`) |
| Pesi, interlinea, spaziatura lettere, maiuscolo, spazio sotto i titoli | `starter_text_style_<livello>_font_weight` (100–900), `_line_height_*`, `_letter_spacing_*`, `_uppercase`, `_margin_bottom_*` |
| Raggio degli angoli (card, immagini, riquadri) | `starter_corner_radius` (`none,sm,md,lg,xl,custom` + `starter_corner_radius_custom`) |
| Pulsanti: raggio, maiuscolo, dimensione, padding | `starter_button_radius` (`0,6,10,16,999,custom`), `starter_button_uppercase`, `starter_button_size`, `starter_button_pad_x/_y` |
| Larghezza del contenuto | `starter_content_width` |

Regole:
- **Google Fonts:** il font deve esistere nell'elenco del tema (`starter_google_fonts_list()`, es. Montserrat, Lato, Poppins, Inter…). Se la style guide usa un font che non c'è, dillo: proponi il più simile oppure "lo carichi tu" come Font 3/4 (file da caricare dall'utente). Dopo l'applicazione i font sono **scaricati sul sito** (cartella `uploads/starter-fonts`, opzione *Prestazioni → Google Fonts serviti dal tuo sito*): lo script lo fa e riporta l'esito; se fallisce, i font restano serviti da Google e va detto.
- **Pesi:** i Google Fonts del tema sono variabili (100–900): il peso si sceglie per livello in `starter_text_style_*_font_weight`, non nel font. Chiedi/ricava almeno il peso dei titoli e del testo.
- **Colori:** sempre esadecimali completi; un colore che nella style guide è solo un nome ("verde bosco") si chiede in esadecimale.
- Senza style guide: chiedi **colori** (sfondo, testo, primario, secondario, accento), **font** (titoli, testo) e **pesi**, con esempi, e proponi tu un abbinamento se l'utente non sa.

## Gruppi di domande

Copri **tutte** le aree delle opzioni; quelle che l'utente non sente sue si chiudono con "lascia com'è".

| Gruppo | Domande | Opzioni |
|---|---|---|
| **1 · Il progetto** | Nome e motto del sito. Tipo di sito (vetrina, servizi, portfolio, immobiliare, negozio, blog). Lingua. Logo chiaro per l'header trasparente (il file lo carica l'utente)? | `blogname`, `blogdescription`, `starter_dark_logo_active` |
| **2 · Moduli** | Portfolio? Immobili? Negozio WooCommerce? Blog? | `starter_module_portfolio`, `starter_module_immobili` (negozio e blog dipendono da plugin e pagine) |
| **3 · Colori** | Palette (da style guide o domande), colore dei link, pulsanti pieni/contorno, colori di footer, menu mobile, social, tag. | vedi tabella sopra; `starter_color_menu*`, `starter_color_social*`, `starter_color_tag*`, `starter_color_footer_form*` |
| **4 · Font e testi** | Font dei titoli e del testo; pesi; dimensioni (standard del tema o da style guide); titoli in maiuscolo? | `starter_font1_*`, `starter_font2_*`, `starter_text_style_*` |
| **5 · Forma e pulsanti** | Angoli: nessuno/piccoli/medi/grandi/molto grandi. Pulsanti: squadrati/arrotondati/a pillola, maiuscolo, dimensione. Larghezza del contenuto. | `starter_corner_radius`, `starter_button_*`, `starter_content_width` |
| **6 · Header** | Versione (standard, centrato, ridotto, pillole). Flottante? Tutta larghezza? CTA (testo e link). Social, ricerca, account, preferiti, carrello? Ordine e stile delle icone? Logo che si rimpicciolisce allo scroll? Sottomenu col click? | `starter_header_*`, `starter_cta_*`, `starter_social_show_header` |
| **7 · Footer, social, avvisi** | Versione (1–5). Titoli e testi del footer, contatti, copyright (`{anno}`, `{sito}`). Social. Privacy/cookie/termini (indirizzi). WhatsApp. Barra annunci (testo, colori, velocità). Spazio libero del footer. | `starter_footer_*`, `starter_social_*`, `starter_whatsapp_*`, `starter_topbar_*` |
| **8 · Pagine e blog** | Briciole di pane (nessuna / nel titolo / in fascia). Caricamento delle pagine (nessuno, dissolvenza, tendina; cosa mostrare). Commenti. Blog: colonne, aside nell'elenco e negli articoli (fissa?), filtri per categoria, articolo in evidenza (come scegliere), sommario, autore, correlati, condividi, larghezza massima dell'articolo. | `starter_breadcrumbs*`, `starter_preloader*`, `starter_disable_comments`, `starter_blog_*` |
| **9 · Prestazioni e tracciamento** | Hosting (LiteSpeed/Hostinger)? Unire CSS/JS e comprimere l'HTML? Immagini AVIF/WebM? (i codici di Tag Manager/Analytics li incolla l'utente) | `starter_perf_*` (i codici stanno in `starter_code_*`, solo con permesso di codice libero) |
| **10 · Portfolio / Immobili / Shop** *(solo se attivi)* | Portfolio: stile delle card degli "Altri progetti" (logo o classica), ordine riassunto/cliente, spazi sotto, transizione tra i progetti. Immobili: modulo "Richiedi informazioni". Shop: colonne, filtri, card (preferiti, carrello, immagine al passaggio, categorie), avviso test. | `starter_portfolio_rel_*`, `starter_portfolio_single_*`, `starter_imm_form_request`, `starter_shop_*` |

Le chiavi esatte e le scelte ammesse si leggono dai controlli del Customizer (`references/opzioni.md`); lo script rifiuta tutto ciò che non esiste o non è ammesso, quindi in dubbio **prova**.

## Applicare il profilo

```
wp eval-file <skill>/scripts/applica-profilo.php profilo.json prova     # solo resoconto
wp eval-file <skill>/scripts/applica-profilo.php profilo.json           # applica (+ scarica i Google Fonts)
```

Formato: `mods` (opzioni del tema), `options` (`blogname`, `blogdescription`), `palette` (lista di `{ "nome", "valore" }`), `modules` (`["portfolio","immobili"]`). Vedi la testata dello script.

- **Prima la tavolozza, poi le scelte che la usano** (sono `slot_N`: numero dello slot): nel file metti `palette` e poi `mods`.
- **Sempre `prova` prima**; mostra il resoconto; applica solo dopo l'ok.
- Mai valori inventati: se non sai un valore (un indirizzo, un link), lascia l'opzione com'è e mettila tra "da fare a mano".
- Le opzioni si scrivono nel tema **attivo** (se è il figlio, nel figlio).

## Da non fare

- Non applicare niente senza proposta e conferma.
- Non chiedere né caricare logo, foto, video, testi delle pagine, menu o moduli di contatto.
- Non fare domande a cui la style guide o le risposte precedenti già rispondono.
- Non toccare siti già avviati o progetti reali di cui il tema è solo "ispirazione".

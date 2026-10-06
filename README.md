# OptiWebSolutions

Site vitrine français/anglais avec Symfony 7.4, Twig, AssetMapper et Stimulus, sans bundler Node.js ni base de données.

## Lancement local

PHP 8.2 ou supérieur et Composer sont nécessaires.

```sh
composer install
symfony server:start --no-tls
```

Ouvrir l’adresse affichée par Symfony, généralement http://127.0.0.1:8000.

Le français est disponible à `/`, l’anglais à `/en`. La page contact et les pages légales ont également leur version sous `/en/`. Le lien de langue conserve la page courante.

Le blog est disponible à `/blog` et `/en/blog`. Les études de cas ont leurs pages sous `/etudes-de-cas` et `/en/case-studies`. Les liens de langue des articles utilisent leur slug traduit.

## Personnalisation

- Accueil et réalisations : `templates/home/index.html.twig` ; captures locales dans `assets/projects/`.
- Page `/contact` : `templates/contact/index.html.twig` ; calendrier Cal.com et FAQ.
- Navigation et pied de page partagés : `templates/layout.html.twig`.
- Logo : `assets/logo.svg`, symbole dans `assets/brand-symbol.svg` et affichage partagé dans `templates/partials/_brand.html.twig`.
- FAQ visible et données structurées : `templates/partials/_faq.html.twig`.
- Design et animations : `assets/app.css`.
- Interactions : contrôleurs Stimulus dans `assets/controllers/`, chargés par `assets/app.js` et `importmap.php`. `assets/theme.js` restaure le thème avant l’affichage pour éviter un clignotement.
- Traductions : `translations/messages.fr.json` et `translations/messages.en.json`, utilisées dans Twig avec `|trans`. Les titres, descriptions, libellés d’accessibilité et données FAQ sont traduits.
- Blog et études de cas : métadonnées, slugs, dates et structure dans `src/Service/EditorialContent.php` ; textes sous les clés `blog.*`, `case.*` et `editorial.*` des catalogues JSON ; affichage dans `templates/editorial/`.
- Couvertures du blog : `assets/blog/`. Les illustrations ont été générées avec l’IA ; les captures des études de cas proviennent des projets présentés. Les images sont servies en WebP avec des variantes de 640 pixels. Les originaux de projet sont conservés.
- Coordonnées : variables `SITE_NAME`, `CONTACT_EMAIL`, `CONTACT_PHONE`, `CONTACT_PHONE_DISPLAY` et `WHATSAPP_NUMBER` dans `.env`, surchargeables dans `.env.local`. Le numéro WhatsApp doit contenir uniquement les chiffres avec l’indicatif international.

Les boutons WhatsApp ouvrent une conversation avec un message prérempli. Les liens e-mail ouvrent la messagerie du visiteur ; il n’y a pas de formulaire ni de serveur d’envoi d’e-mails.

La réservation intégrée utilise l’événement `optiwebsolutions/15min`. Pour changer l’événement, renseigner `CALCOM_EVENT` dans `.env.local` avec son chemin, par exemple `votre-nom/appel-decouverte` pour `https://cal.com/votre-nom/appel-decouverte`. Le calendrier officiel Cal.com se charge uniquement sur la page contact, à l’approche du calendrier. Sans lien configuré, les visiteurs peuvent convenir d’un rendez-vous par e-mail.

Les réalisations présentent Les-Consultants, Lnstrade, ULTRAPOP, le catalogue interactif et MYKEYNEST. Les tableaux de bord Les-Consultants et MYKEYNEST utilisent les captures fournies ; les informations de compte visibles dans MYKEYNEST sont floutées dans le fichier publié. La section « Idées » présente des exemples de projets web, d’applications et d’infogérance.

Pour la production, pointer le serveur web sur `public/`, utiliser `APP_ENV=prod`, `APP_DEBUG=0` et définir un `APP_SECRET` propre au déploiement.

Les sources des polices, icônes et images sont dans `assets/`, avec leurs licences. AssetMapper génère leurs URL avec empreinte. Lorsque la réservation est activée, le calendrier et le script d’intégration sont chargés depuis Cal.com, dans la langue de la page.

## Assets en production

Après `composer install`, compiler les assets pour le déploiement :

```sh
php bin/console cache:clear --env=prod --no-warmup
php bin/console asset-map:compile --env=prod
```

Le cache de production doit être renouvelé après une mise à jour du code pour que les empreintes des assets reflètent les nouveaux fichiers.

La sortie dans `public/assets/` est générée et ignorée par Git. En développement, AssetMapper sert les sources directement ; supprimer ce dossier généré après une compilation locale permet de retrouver la prise en compte immédiate des modifications.

## Référencement et publication

`DEFAULT_URI` définit l’origine publique utilisée par les URL canoniques, les liens de langue, les données structurées, les partages sociaux et le sitemap. Sa valeur par défaut est `https://optiwebsolutions.fr`. La surcharger dans `.env.local` si le domaine public change, sans barre oblique finale.

- `/sitemap.xml` liste les pages HTML canoniques françaises et anglaises et les images des contenus. Les liens `hreflang` réciproques figurent dans le HTML des pages. Les dates `modified` des contenus sont explicites ; les modifier lors d’une vraie mise à jour du contenu.
- `/robots.txt` autorise les contenus publics et indique le sitemap.
- `/llms.txt` présente les informations essentielles et les liens vers les versions Markdown des articles et études de cas. Les pages HTML annoncent ces versions avec `rel="alternate"` et le fichier avec `rel="describedby"`. Les versions `.md` reprennent les mêmes textes et portent `X-Robots-Tag: noindex, follow` pour éviter des résultats dupliqués. Ce fichier suit une proposition documentaire et ne garantit pas une présence dans les réponses d’une IA.
- `/blog/feed.xml` et `/en/blog/feed.xml` fournissent les flux RSS.
- Les données JSON-LD décrivent l’organisation, le site, les services visibles, les articles, le fil d’Ariane et les FAQ. Les avis, les statistiques clients et les résultats non mesurés ne sont pas ajoutés.

Sur Apache/o2switch, utiliser `public/` comme racine du domaine. `public/.htaccess` assure le routage, la compression des textes et le cache long des assets dont le nom possède une empreinte ; les modules Apache correspondants doivent être disponibles. Les fichiers d’indexation et flux sont mis en cache cinq minutes.

Après déploiement, vérifier le domaine canonique, déclarer le site dans Google Search Console et soumettre `/sitemap.xml`. Suivre ensuite l’indexation, les recherches et les prises de contact. L’optimisation technique facilite la découverte du contenu ; le classement dépend aussi de sa pertinence et de la concurrence.

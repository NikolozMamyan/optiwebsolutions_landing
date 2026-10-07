# Rapport SEO — OptiWebSolutions

**Analyse du 7 octobre 2026 · Site : https://optiwebsolutions.fr/**

Document étudié : « audit-seo (1).pdf », 16 pages, rapport Lou / LIMOVA.AI. Analyse complétée par la lecture du code Symfony, 14 contrôles HTTP sur le site en production et un test du consentement Analytics dans un navigateur.

## 1. Diagnostic et ordre de travail

**La base technique est solide. Les premières corrections concernent les redirections ; le développement de la visibilité doit ensuite porter sur les prestations, les preuves de réalisation et les liens entrants.**

L'audit attribue une note globale **B**, avec A− en référencement, A+ en GEO, F en liens, B+ en convivialité et B+ en performance. Ce sont les notes de son outil, pas des notes Google ni une mesure du potentiel commercial.

Les 19 recommandations mélangent des corrections utiles, des pistes commerciales et des détections à nuancer. Le Pixel Facebook, cinq comptes sociaux ou la suppression de petits styles CSS intégrés ne doivent pas passer avant les problèmes confirmés.

| Ordre | Action | Pourquoi maintenant | Validation attendue |
| --- | --- | --- | --- |
| 1 | Unifier HTTPS et le domaine sans www | Les quatre variantes répondent actuellement en 200 | Une redirection permanente vers la même page sur https://optiwebsolutions.fr |
| 2 | Remplacer la redirection globale des 404 | Les pages inexistantes renvoient en 302 vers l'accueil | Une vraie réponse 404 avec une page utile ; redirections uniquement vers un contenu équivalent |
| 3 | Valider Search Console et la mesure des contacts | L'indexation, les requêtes et les prospects ne sont pas mesurables dans le PDF | Sitemap traité, pages commerciales inspectées, événements vérifiés dans GA4 |
| 4 | Clarifier l'accueil et créer les pages de prestations | L'accueil présente l'offre, mais son H1 reste très général | Une page distincte par besoin commercial prioritaire, reliée aux projets et au contact |
| 5 | Renforcer les preuves et les liens entrants | L'audit ne trouve que deux domaines référents | Des références vérifiables et des liens éditoriaux pertinents |
| 6 | Développer le référencement local si l'activité est éligible | Strasbourg / Alsace peut apporter des prospects proches | Une fiche Google réelle et cohérente avec l'activité, si les critères sont remplis |

## 2. Ce qui fonctionne déjà

L'audit et les vérifications confirment plusieurs acquis :

- Un titre et une description qui présentent l'activité et Strasbourg.
- Du contenu disponible dans le HTML initial, avec un H1 et des sections structurées.
- Des versions française et anglaise, avec canonical et hreflang sur l'accueil.
- Un sitemap XML accessible et correctement analysable : **50 URL et 36 entrées d'images** au moment du contrôle.
- Un robots.txt accessible, qui déclare le sitemap HTTPS ; un llms.txt également accessible.
- Des données structurées Organization, WebSite et WebPage ; Service sur l'accueil ; Article / BlogPosting et BreadcrumbList dans les modèles éditoriaux.
- Des images WebP avec dimensions explicites et variantes adaptées aux écrans.
- Des métadonnées de partage Open Graph et Twitter.

Le PDF mesure **97/100 sur mobile et 100/100 sur ordinateur**. Son test mobile indique un LCP de 2,3 s, un CLS de 0,009 et un temps de blocage de 0 s. Cela ne justifie pas de classer la vitesse parmi les gros chantiers actuels. Ces chiffres proviennent du PDF ; ils n'ont pas été remesurés ici.

L'audit manque de données terrain CrUX. Les bons résultats de laboratoire ne prouvent donc pas que les Core Web Vitals sont validés pour les visiteurs réels : il faut suivre LCP, INP et CLS lorsque ces données deviennent disponibles. [Google — Core Web Vitals](https://developers.google.com/search/docs/appearance/core-web-vitals).

## 3. Corrections techniques prioritaires

### 3.1 HTTPS et domaine canonique — défaut confirmé

| URL contrôlée sans suivre les redirections | Réponse actuelle |
| --- | --- |
| http://optiwebsolutions.fr/ | 200 |
| https://optiwebsolutions.fr/ | 200 |
| http://www.optiwebsolutions.fr/ | 200 |
| https://www.optiwebsolutions.fr/ | 200 |
| http://optiwebsolutions.fr/blog/creation-site-web-strasbourg?audit=seo | 200 |

La canonical HTTPS présente dans le HTML est utile, mais ne remplace pas la redirection des variantes.

**À faire :** choisir https://optiwebsolutions.fr comme destination unique et configurer une redirection serveur 301 ou 308, idéalement en un saut. Conserver le chemin et les paramètres : un article demandé en HTTP doit aboutir au même article en HTTPS, pas à l'accueil. Vérifier la configuration de l'hébergement avant de placer la règle dans le .htaccess effectivement utilisé. [Google — Redirections et changement d'URL](https://developers.google.com/search/docs/crawling-indexing/site-move-with-url-changes).

**Fichiers concernés lors de l'implémentation :** .htaccess, public/.htaccess, éventuellement la configuration du domaine chez o2switch. Les deux fichiers actuels ne contiennent pas de règle d'unification HTTPS / www.

### 3.2 Pages inexistantes — point important absent des recommandations du PDF

Deux URL volontairement inexistantes ont été testées : la version française répond **302 vers /**, la version anglaise **302 vers /en**. Le comportement vient de `src/EventSubscriber/NotFoundRedirectSubscriber.php`.

**À faire :** afficher une page 404 soignée, en français ou en anglais, avec des liens vers l'accueil, les prestations et le contact, tout en conservant le statut HTTP 404. Pour une ancienne page réellement remplacée, établir une redirection permanente vers son équivalent. Une URL supprimée sans remplacement doit rester en 404, ou en 410 si sa suppression est intentionnellement définitive.

Google indique que des redirections vers un accueil sans rapport avec le contenu demandé peuvent être traitées comme des **soft 404**. Ce risque est confirmé par le comportement du site ; sa classification effective par Google reste à consulter dans Search Console. [Google — Éviter les redirections non pertinentes](https://developers.google.com/search/docs/crawling-indexing/site-move-with-url-changes).

### 3.3 Search Console, Analytics et contacts

Le constat « Analytics absent » du PDF est à corriger : **G-0PZCLF18Z1 est présent en production**. Le test navigateur confirme l'absence de chargement de la balise avant consentement, puis son insertion et sa configuration après acceptation. Les requêtes Google ont été simulées pendant ce test ; la réception des données dans le compte n'a pas été vérifiée.

Le fonctionnement observé correspond à un chargement conditionné au consentement, ce qui explique vraisemblablement la non-détection par l'audit. Il faut conserver ce comportement plutôt que charger Analytics automatiquement pour améliorer sa note. [Google — Mode de consentement basique](https://developers.google.com/tag-platform/security/concepts/consent-mode#basic_consent_mode).

**À faire :**

1. Vérifier ou créer la propriété Domaine dans Search Console, puis y soumettre le sitemap actuel.
2. Inspecter quelques URL représentatives : accueil, contact, article et étude de cas, en FR et EN. Comparer canonical déclarée et canonical choisie par Google.
3. Vérifier un parcours de test consenti dans les rapports de diagnostic GA4.
4. Mesurer les clics WhatsApp, téléphone, e-mail et prise de rendez-vous, sans envoyer de données personnelles dans les paramètres.
5. Distinguer un clic vers Cal.com d'un rendez-vous réellement réservé : la confirmation nécessite une intégration adaptée, à étudier séparément.

Search Console permettra de suivre impressions et clics de recherche. GA4 ne représentera qu'une partie des visiteurs, notamment ceux ayant accepté la mesure. Ni l'un ni l'autre ne permet de déduire qu'un clic de contact est déjà un prospect qualifié.

## 4. Contenus à travailler pour attirer des clients

### 4.1 Rendre l'offre immédiatement compréhensible

Le H1 actuel est « Le digital qui fait avancer votre entreprise ». Il convient au ton de marque, mais précise peu les prestations. Une proposition à valider est : **« Sites web et applications sur mesure à Strasbourg »**. La phrase actuelle peut rester comme accroche secondaire, avec un texte précisant l'accompagnement en Alsace et à distance en France.

Les mots fréquents relevés par l'audit, comme « et », « le » ou « projet », ne constituent pas une étude de mots-clés. Il faut choisir les sujets selon l'intention des prospects et les données Search Console, sans multiplier artificiellement les répétitions.

### 4.2 Créer des pages de prestations distinctes

L'approche recommandée est de conserver l'accueil synthétique et d'ajouter progressivement les pages commerciales suivantes. **Ce sont des propositions de contenus, pas des pages déjà créées ; leurs requêtes restent à valider, aucun volume de recherche n'a été mesuré ici.**

| Page proposée | Intentions à étudier | Preuves à relier |
| --- | --- | --- |
| Création de sites web à Strasbourg | création site internet Strasbourg, site vitrine Alsace | Lnstrade, méthode, adaptation mobile, contact |
| Applications métier et développement Symfony | application métier sur mesure, développeur Symfony, développement SaaS | Les-Consultants et MYKEYNEST |
| Boutique en ligne sur mesure | création boutique en ligne, site e-commerce sur mesure | ULTRAPOP |
| Catalogue digital interactif | catalogue digital, catalogue produits interactif | Projet de catalogue, démonstration des usages |

Chaque page doit expliquer le besoin, la solution, les livrables, les étapes, les facteurs de budget et les limites du périmètre, puis présenter une réalisation pertinente et un CTA. Une page d'infogérance pourra suivre si cette prestation est une priorité commerciale réelle.

Éviter de créer une série de pages identiques dont seul le nom de ville ou de pays change. L'anglais doit être adapté aux clients réellement visés : sa simple présence ne prouve pas une pertinence commerciale dans toute l'Europe.

### 4.3 Relier le blog aux prestations et aux études de cas

Le projet contient déjà **13 articles et 5 études de cas**, chacun en français et en anglais. Le premier travail est de les relire, enrichir et relier aux pages commerciales. Les deux articles mis en avant sur l'accueil portent actuellement sur le SEO et l'IA ; équilibrer cette sélection avec un sujet proche d'une décision d'achat, comme la création ou la refonte d'un site.

Parcours souhaité : **article répondant à une question → prestation correspondante → réalisation pertinente → contact**. Les liens doivent être utiles et intégrés au contexte, avec un intitulé descriptif.

Pour les études de cas, compléter les informations réelles disponibles : besoin initial, rôle exact d'OptiWebSolutions, choix techniques, fonctionnalités livrées, captures autorisées et retour du client. Ajouter des résultats chiffrés seulement lorsqu'ils sont vérifiables. Pour les articles, identifier le rédacteur ou le relecteur lorsqu'il a réellement participé ; l'auteur Organization actuel est valide, mais une expertise personnelle présentée clairement peut mieux rassurer le lecteur.

Google recommande des contenus apportant une valeur originale et une expertise démontrable. L'IA peut aider la rédaction ; les exemples vécus, les vérifications et la pertinence pour le client restent à apporter. [Google — Contenu utile et fiable](https://developers.google.com/search/docs/fundamentals/creating-helpful-content).

## 5. Autorité, confiance et visibilité locale

### 5.1 Liens entrants : un chantier utile, à confirmer avec plus de données

Le PDF annonce **3 backlinks provenant de 2 domaines** et un indicateur de force de domaine de 1. Ces données viennent de son fournisseur ; elles ne constituent pas un inventaire exhaustif ni une métrique Google. Les domaines cités ne suffisent pas à démontrer une réputation dans le développement web.

**À faire :** consulter les liens connus dans Search Console, puis développer des références pertinentes : présentation d'un projet sur le site d'un client s'il le souhaite, témoignage documenté, partenariat professionnel, participation à une ressource métier ou publication locale apportant une information réelle. Privilégier la pertinence et la qualité.

Éviter les achats de liens destinés au classement et les inscriptions en masse sur des annuaires médiocres. Ne pas désavouer automatiquement les deux liens du PDF. Les liens sortants vers des sources utiles ne sont pas, à eux seuls, une fuite à corriger en ajoutant `nofollow` partout. [Google — Règles sur le spam de liens](https://developers.google.com/search/docs/essentials/spam-policies#link-spam).

### 5.2 Google Business Profile : intéressant sous condition

L'audit n'a pas identifié de fiche ; il ne prouve pas qu'aucune n'existe. Vérifier d'abord la fiche éventuelle et les conditions d'activité.

Si OptiWebSolutions reçoit des clients ou se déplace réellement chez eux, une fiche peut être pertinente pour Strasbourg / Alsace. Une activité exclusivement en ligne n'est pas éligible. Un établissement de services avec déplacements peut, selon sa situation, masquer son adresse publique ; cela ne dispense pas de fournir à Google une adresse réelle pour la validation. [Google — Éligibilité des établissements](https://support.google.com/business/answer/13763036?hl=fr).

Après vérification : renseigner les prestations, coordonnées, zone desservie, photos réelles et recueillir des avis authentiques. Ne pas inventer d'adresse, d'horaires, d'avis ou de coordonnées géographiques pour compléter un score.

### 5.3 Identité et données structurées

La page `/legal` contient déjà le nom Nikoloz Mamyan et le SIRET : le signalement « informations d'enregistrement absentes » porte principalement sur l'accueil. Rendre les informations et le lien vers l'agence faciles à trouver, puis ajouter aux données Organization les profils officiels existants lorsque leurs URL sont confirmées.

Le balisage LocalBusiness est une option à examiner selon les informations réelles disponibles. Il ne faut pas remplacer systématiquement Organization ni ajouter une adresse fictive pour obtenir une validation.

Le A+ GEO du PDF ne démontre pas des citations dans ChatGPT ou Google IA. Le fichier llms.txt peut rester utile à certains outils, mais **Google précise qu'il n'améliore pas sa visibilité ni ses classements**. Le travail prioritaire reste le contenu original, accessible et fiable. [Google — Optimisation pour les fonctionnalités IA](https://developers.google.com/search/docs/fundamentals/ai-optimization-guide).

## 6. Décision sur les 19 recommandations du PDF

| Nº | Recommandation de l'audit | Décision pour le projet |
| --- | --- | --- |
| 1 | Développer les backlinks | Retenir : liens pertinents et naturels, inventaire à compléter |
| 2 | Rediriger vers HTTPS | Prioritaire : défaut confirmé ; unifier aussi www |
| 3 | Ajouter les mots-clés aux balises | Retenir avec discernement : clarifier le H1 et les pages de prestations |
| 4 | Améliorer la vitesse | Surveiller : les mesures du PDF sont déjà très bonnes |
| 5 | Différer les ressources bloquantes | Investiguer seulement une ressource précisément mesurée ; préserver l'affichage |
| 6 | Remplir tous les ALT | Nuancer : aucune des 10 images de l'accueil n'omet l'attribut ; 5 ont un ALT vide |
| 7 | Améliorer les ancres de liens | Revoir les liens ambigus, en tenant compte du nom accessible et des liens répétés |
| 8 | Créer Google Business Profile | Vérifier l'existant et l'éligibilité ; pertinent pour une activité locale admissible |
| 9 | Ajouter LocalBusiness | Conditionnel : Organization existe ; utiliser seulement des données réelles |
| 10 | Installer Analytics | Déjà intégré : vérifier la réception et compléter la mesure des contacts |
| 11 | Créer Facebook | Facultatif selon l'audience et la capacité à l'animer |
| 12 | Créer X | Facultatif ; aucune priorité SEO établie dans cet audit |
| 13 | Créer Instagram | Facultatif selon la stratégie commerciale |
| 14 | Créer LinkedIn | À privilégier si prospection B2B active ; confirmer le profil existant |
| 15 | Créer YouTube | Facultatif si des démonstrations utiles peuvent être produites |
| 16 | Installer le Pixel Facebook | À envisager pour une campagne Meta ; aucune nécessité pour ce chantier SEO |
| 17 | Supprimer les styles intégrés | Pas de chantier global : les variables de couleur et de visuels sont intentionnelles |
| 18 | Masquer l'e-mail en clair | Garder le contact accessible ; l'alerte concerne surtout la collecte automatisée |
| 19 | Renforcer les signaux de confiance GEO | Retenir : références, auteur réel, preuves et cohérence des informations |

Concernant les ALT, les cinq valeurs vides correspondent aux deux illustrations d'articles et aux trois vignettes d'études de cas. Leur contenu est accompagné de liens textuels ; les liens d'image répétés sont masqués aux technologies d'assistance. Un `alt=""` est approprié pour une image décorative ou redondante. Revoir les images réellement informatives et améliorer leurs descriptions sans ajouter des mots-clés à toutes les illustrations. [W3C — Images décoratives](https://www.w3.org/WAI/tutorials/images/decorative/).

Le PDF détecte aussi Bootstrap. Les dépendances examinées utilisent Stimulus et AssetMapper ; cette détection semble confondre le fichier `stimulus_bootstrap.js` avec le framework Bootstrap. Elle ne justifie pas un changement d'architecture.

## 7. Plan d'exécution et critères de réussite

**Semaine 1 — Fiabiliser les fondations.** Corriger les variantes d'URL et les 404, puis vérifier Search Console et GA4. Documenter un état initial des pages indexées, requêtes et contacts. Les changements serveur doivent être testés sur l'accueil, un article, `/en`, les assets et les paramètres d'URL pour éviter les boucles.

**Semaine 2 — Clarifier l'offre.** Ajuster l'accueil et publier les deux pages de prestations les plus importantes commercialement, avec leurs traductions et des liens depuis la navigation ou les sections pertinentes. Les nouvelles pages doivent avoir titre, description, H1, canonical et hreflang cohérents, être présentes dans le sitemap et reliées au contact.

**Semaines 3 et 4 — Ajouter de la preuve.** Enrichir deux études de cas, améliorer le maillage des articles existants, préparer des références professionnelles et compléter la fiche locale si elle est éligible. Optimiser ensuite les autres pages uniquement à partir de problèmes mesurés, notamment le contact avec Cal.com et les grandes images éditoriales.

**Chaque mois — Mesurer et ajuster.** Suivre les clics et impressions hors marque, les requêtes commerciales par page et par pays, les prises de contact et les demandes qualifiées. Mesurer séparément les clics de contact et les rendez-vous confirmés. Examiner les pages exclues de l'indexation et les éventuelles soft 404 avant de produire de nouveaux contenus.

Le calendrier décrit l'ordre du travail ; il ne promet pas un délai de classement ni un nombre de clients. Une hausse de la note de l'outil n'est pas le critère principal de réussite : ce sont la visibilité sur les besoins visés et les demandes pertinentes.

## 8. Périmètre, éléments manquants et fichiers concernés

Cette analyse couvre les 16 pages du PDF, les fichiers Symfony pertinents et des vérifications ciblées en production. Elle n'est pas un crawl exhaustif, une étude concurrentielle complète ou une nouvelle campagne Lighthouse. Les comptes privés GA4 et Search Console n'ont pas été consultés ; les positions, volumes de recherche, backlinks exhaustifs et conversions réelles restent inconnus.

Pour finaliser les choix de contenus : disposer des données Search Console, de la liste des prestations prioritaires, des preuves et témoignages autorisés, et des informations sur la fiche Google éventuelle et l'accueil ou les déplacements chez les clients.

Les changements futurs concerneraient principalement :

- `.htaccess` et `public/.htaccess` : redirections de domaine et protocole.
- `src/EventSubscriber/NotFoundRedirectSubscriber.php` : gestion des réponses 404.
- `templates/home/index.html.twig`, `translations/messages.fr.json` et `translations/messages.en.json` : précision de l'offre et contenus bilingues.
- `assets/controllers/analytics_controller.js` et les boutons concernés : mesure des contacts après consentement.
- `templates/base.html.twig`, les modèles éditoriaux et `src/Service/SearchIndex.php` : adaptation ciblée des métadonnées, liens et sitemap aux nouvelles pages.

**Livrable de cette étape :** ce rapport et sa version PDF. Les corrections applicatives et serveur restent le travail de l'étape d'implémentation.

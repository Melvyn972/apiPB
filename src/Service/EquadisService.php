<?php

// src/Service/EquadisService.php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class EquadisService
{
    private $client;
    private $sessionCookie;
    private $keyMapping = [
        /*"1" => "Type d'unité commerciale",
        "10" => "Catégorie de produits",
        "101" => "Gamme de produit",
        "102" => "Dénomination de vente",
        "1024" => "Indication d'emballage réutilisable",
        "1025" => "Type d'emballage",
        "1026" => "Poids de l'emballage",
        "1028" => "Type de support palette",
        "103" => "Libellé long",
        "1033" => "Type de matériaux d'emballage",
        "1034" => "Matériau récupérable",
        "1039" => "Quantité de matériau",
        "1040" => "Quantité de matériau (code unité de mesure)",
        "1043" => "Épaisseur du matériau",
        "1044" => "Épaisseur du matériau (code unité de mesure)",
        "1045" => "Code couleur du matériau",
        "106" => "Variante produit",
        "1075" => "Clause de non responsabilité",
        "1076" => "Numéro de lot",
        "1078" => "Emballage marqué retournable",
        "1079" => "Prix marqué sur l'emballage",
        "1080" => "Produit recyclable",
        "1081" => "Informations promotionnelles sur le produit",
        "1082" => "Sigles/Accréditations marqués sur le packaging",
        "1083" => "Langues imprimées sur emballage",
        "1086" => "Avertissement",
        "1087" => "Label Spécifique France",*/
        "109" => "Marque",
       /* "1092" => "Format de date",
        "1094" => "Date de production marquée sur l'emballage",
        "11" => "Brique GPC",
        "112" => "Sous-marque",
        "112_1" => "Type de marque",
        "113" => "Couleur",
        "113_0" => "Code couleur",
        "114" => "Description de la couleur",
        "117" => "Profondeur",
        "118" => "Profondeur (code unité de mesure)",
        "119" => "Diamètre",
        "120" => "Diamètre (code unité de mesure)",
        "121" => "Hauteur",
        "122" => "Hauteur (code unité de mesure)",
        "1224" => "Type de nomenclature douanière",
        "1225" => "Code de nomenclature douanière",
        "1226" => "Mesure statistique des rapports",
        "1229" => "Pays d'origine",
        "1232" => "Type d'activité",
        "1233" => "Région d'origine (du type d'activité)",
        "1234" => "Pays d'activité",
        "126" => "Contenance nette",
        "1260" => "Variété",
        "127" => "Contenu net (code unité de mesure)",
        "13" => "Attribut GPC",
        "132" => "Largeur",
        "133" => "Largeur (code unité de mesure)",
        "134" => "Quantité nette",
        "1354" => "Conformité/réglementations spécifiques",
        "1355" => "Acte réglementaire associé à l'autorisation",
        "1356" => "Organisme règlementaire associé à l'autorisation",
        "1357" => "Conforme à la réglementation",
        "1359" => "Date de fin de l'autorisation",
        "1360" => "Date de début de l'autorisation",
        "1361" => "Identification de l'autorisation",
        "1370" => "Numéro de FDS / FITPC",
        "1371" => "Fiche de Données de Sécurité (FDS) / FITPC",
        "1381" => "Système d'identification Ingrédient Chimique",
        "1383" => "Concentration de l'ingrédient chimique",
        "1384" => "Concentration de l'ingrédient chimique (code unité de mesure)",
        "1386" => "Numéro d'identification Ingrédient Chimique",
        "1387" => "Nom de l'ingrédient chimique",
        "1399" => "Indication de l'espèce animale sur laquelle le produit a été testé",
        "14" => "Valeur de l'attribut GPC",
        "1400" => "Propriété du Produit",
        "1401" => "Type de Traitement",
        "1402" => "Type de Peau",
        "1403" => "Utilisation du produit",
        "1404" => "Texture du Produit",
        "1405" => "Période de commercialisation",
        "1406" => "Code risque environnemental",
        "1408" => "Exclusivité distributeur",
        "1408_0" => "Certificat CITES requis",
        "1409" => "Article cellophané",
        "1409_1" => "Mention de danger (Phrase H ou EUH)",
        "141" => "Profondeur",
        "1412" => "Conseils de prudence",
        "142" => "Profondeur hors emballage (code unité de mesure)",
        "1428" => "Point Éclair",
        "1429" => "Point Eclair (code unité de mesure)",
        "1435" => "pH",
        "144" => "Hauteur",
        "145" => "Hauteur hors emballage (code unité de mesure)",
        "1453" => "Agence de maintenance des rubriques ICPE",
        "1454" => "Rubrique ICPE",
        "146" => "Largeur",
        "1463" => "Température d'ébullition",
        "1464" => "Température d'ébullition (unité de température)",
        "147" => "Largeur hors emballage (code unité de mesure)",
        "1496" => "Déclaration pertinente du Prix de Base",
        "1499" => "Mesure / affichage du prix",
        "1501" => "Type de Mesure / affichage du prix",
        "1503" => "Unité de mesure utilisée pour la Facture",
        "1520" => "Contient des pesticides",
        "1544" => "Type de code à barres",
        "1546" => "Méthode de culture",
        "1555" => "Nom de la directive sur les déchets",
        "1558" => "Instructions de manutention",
        "156" => "Poids brut",
        "1560" => "Facteur de gerbage",
        "1562" => "Poids maximum de gerbage",
        "1563" => "Poids maximum de gerbage (code unité de mesure)",
        "1568" => "Nombre de sous conditionnements non identifiés par un GTIN",
        "157" => "Poids brut (code unité de mesure)",
        "1570" => "Nombre d'articles de rang inférieur par sous conditionnement",
        "158" => "Poids net",
        "1581" => "Humidité maximale",
        "1582" => "Humidité minimale",
        "1584" => "Licence (propriétaire)",
        "1589" => "Licence (héros)",
        "159" => "Poids net (code unité de mesure)",
        "1590" => "Licence (titre)",
        "1593" => "Durée de vie minimale / livraison",
        "1594" => "Durée de vie minimale / livraison (code unité de mesure)",
        "1595" => "Date de Durabilité Minimale (DDM)",
        "1596" => "DDM (code unité de mesure)",
        "1599" => "Période Après Ouverture",
        "160" => "Type de produit (Fond de rayon/promotion/autre)",
        "1600" => "Période Après Ouverture (code unité de mesure)",
        "1601" => "Contenance commerciale",
        "1607" => "Température maximale de stockage",
        "1608" => "Température maximale de stockage (code unité de température)",
        "161" => "Quantité gratuite",
        "1611" => "Température minimale de stockage",
        "1612" => "Température minimale de stockage (code unité de température)",
        "162" => "Quantité gratuite produit fils (code unité de mesure)",
        "1626" => "Quantité limitée",
        "1629" => "Agence de réglementation des marchandises dangereuses",
        "163" => "Quantité gratuite (%)",
        "1630" => "Organisme de réglementation",
        "1634" => "Classe de danger (transport)",
        "1637" => "Groupe d'emballages",
        "1638" => "Désignation officielle de transport",
        "1640" => "Nom technique de marchandise dangereuse",
        "1646" => "Numéro ONU de marchandises dangereuses",
        "1647" => " Étiquette de transport",
        "1647_1" => "Numéro séquentiel de l'étiquette de transport",
        "1649" => "Article à mesure variable",
        "1650" => "Type de produit à mesure variable",
        "166" => "Type de promotion",
        "167" => "Code de l'article standard référent",
        "1673" => "Description de la garantie",
        "1674" => "Type de date de prise d'effet de la garantie",
        "1675_1" => "Type Garantie",
        "1677" => "Durée de la garantie",
        "1678" => "Durée de la garantie (code unité de mesure)",
        "1689" => "Classe et catégorie de danger CLP",
        "169" => "GTIN remplacé",
        "1706" => "Date de création/dernière mise à jour de la FDS/FITPC",
        "1707" => "Composant majoritaire",
        "1708" => "Conforme à la réglementation Emark",
        "1709" => "Pays de facturation",
        "171" => "GTIN substitué",
        "1719" => "Taxe parafiscale incluse",
        "172" => "GLN du propriétaire de la marque commerciale",
        "1721" => "Type de paiement des droits",
        "1722" => "Marché cible",
        "1723" => "Date Limite de Consommation (DLC)",
        "1724" => "DLC (code unité de mesure)",
        "1725" => "Poids de l'emballage (code unité de mesure)",
        "1726" => "Mesure statistique des rapports (code unité de mesure)",
        "174" => "Nom du propriétaire de la marque commerciale",
        "1791" => "Caractéristiques métier",
        "1800" => "Format du Produit",
        "185_1" => "Type du contact",
        "185_2" => "Nom du contact",
        "185_3" => "Adresse du contact",
        "185_4" => "GLN du contact",
        "185_5" => "Marché cible",
        "185_6" => "Type",
        "185_7" => "Valeur",
        "1884" => "Destinataire cible",
        "190" => "Nombre de portions exact",
        "1919" => "GTIN Equivalent",
        "1920" => "Histoire de la Marque",
        "1922" => "Date de fin de validité de la variante",
        "1923" => "Code d'identification de la variante",
        "1924" => "Raison de création d'une variante",
        "193" => "Nombre de portions approximatif",
        "198" => "Instructions de préparation (mode d'emploi)",
        "2" => "GTIN de l'article déclaré",
        "2003" => "Date de référence à partir de laquelle la période de disponibilité des pièces détachées est comptée",
        "2004" => "Période de disponibilité des pièces détachées",
        "2005" => "Période de disponibilité des pièces détachées (Unité de mesure)",
        "2044" => "Date de création",
        "2047" => "Type de présentoir",
        "2107" => "Marché cible",
        "2109" => "Description de l'étiquette",
        "2119" => "Dangereux pour l'eau",
        "2158" => "Groupe d'âge cible",
        "2223" => "Ville",
        "2224" => "Pays",
        "2225" => "Code postal",
        "2226" => "Province / Etat",
        "2227" => "Rue",
        "2230" => "Date de début de vente au consommateur",
        "2231" => "Date de début de disponibilité à l'expédition",
        "2232" => "Date de fin de disponibilité à la commande",
        "2233" => "Date de fin de disponibilité à l'expédition",
        "2234" => "Date de début de disponibilité à la commande",
        "2235" => "Destinataire cible",
        "2237" => "Message marketing",
        "2238" => "Libellé étendu (description)",
        "2239" => "Message marketing court",
        "2240" => "Mot ou phrase clé",
        "2241" => "Destinataire cible",
        "2243" => "Type de critère",
        "2244" => "Mesure minimale",
        "2245" => "Unité de mesure",
        "2246" => "Mesure maximale",
        "2247" => "Unité de mesure",
        "2248" => "Histoire de l'article",
        "2261" => "Prix de base",
        "2262" => "Base d'application du prix catalogue",
        "2263" => "Unité de mesure",
        "2264" => "Date de début de validité du prix catalogue",
        "2265" => "Date de fin de validité du prix catalogue",
        "2266" => "Destinataire cible",
        "2273" => "Teneur en sucre",
        "2274" => "Teneur en sucre (unité de mesure)",
        "2279" => "Allégation sociétale",
        "2280" => "Allégation environnementale",
        "2281" => "Code couleur client",
        "2306" => "Indicateur pour garantie commerciale payante",
        "2308" => "Origine animale de la matière première",
        "2310" => "Type de famille de code à barres",
        "2325" => "Code externe",
        "2328" => "Nombre d'UVC",
        "2335" => "Date de première demande",
        "2392" => "Définition de l'ingrédient",
        "2394" => "Type d'activité",
        "2395" => "Pays",
        "2397" => "Région/Département",
        "249" => "Liste des allergènes (laisser vide si pas d'allergène)",
        "250" => "Validation des allergènes (détrompeur allergènes)",
        "252" => "Allergène",
        "253" => "Niveau de présence de l'allergène",
        "2533" => "Code de précision des portions par produit",
        "2534" => "Grade du produit",
        "2541" => "Région d'origine",
        "2542" => "Code devise",
        "2544" => "Quantité de matériau",
        "2545" => "Quantité de matériau (code unité de mesure)",
        "2546" => "Épaisseur du matériau",
        "2547" => "Épaisseur du matériau (code unité de mesure)",
        "2548" => "Code couleur du matériau",
        "255" => "Liste des ingrédients",
        "2550" => "Libellé fiscal",
        "2551" => "Type de matériau",
        "2556" => "Montant de la consigne",
        "2557" => "Code devise",
        "257" => "Validation des ingrédients (détrompeur ingrédients)",
        "2579" => "GTIN Upsell",
        "2580" => "GTIN cross sell",
        "2612" => "Type de format de pile/batterie",
        "2613" => "Pile/batterie rechargeable",
        "2614" => "Niveau de conformité à la règlementation",
        "2615" => "Variété",
        "2617" => "Niveau d'emballage",
        "262" => "Sequence de l'ingrédient",
        "2633" => "Déchets dangereux",
        "2634" => "Réglementé pour le transport",
        "2636" => "Description du formulaire",
        "264" => "Pourcentage dans la portion",
        "265" => "Nom de l'ingrédient",
        "265_1" => "Origine de l'ingrédient",
        "2713" => "Force du goût de base",
        "2714" => "Type de goût pour la bière",
        "2715" => "Quantité de batteries/piles incluses",
        "2719" => "Couleur de la boisson alcoolisée",
        "2720" => "Dosage en Sucre",
        "2721" => "Code appellation vins",
        "2722" => "Cépages / Variétés de raisins",
        "2723" => "Amertume d'une bière",
        "2724" => "Amertume d'une bière (Code unité de mesure)",
        "2725" => "Type de bière",
        "2787" => "Mention d'avertissement",
        "2788" => "Code de classification transport",
        "2789" => "Marchandises dangereuses",
        "2794" => "Disponibilité finale pour le consommateur",
        "2810" => "Elément d'emballage",
        "2811" => "Code du type de processus de recyclage des emball ages",
        "2813" => "Matière première de l'emballage",
        "2814" => "Pourcentage de la teneur en type de matière première",
        "2824" => "Facteur de gerbage",
        "2825" => "Type de gerbage",
        "2826" => "Poids maximum de gerbage",
        "2827" => "stackingWeightMaximum (unité de mesure)",
        "2828" => "Type de motif d’empilement",
        "2835" => "Pays d'origine de l'ingrédient",
        "2845" => "Niveau d'emballage",
        "2846" => "Type d'emballage",
        "2858" => "Type d'emballage description",
        "2860" => "Prix de vente suggéré",
        "2863" => "Unité de mesure (Base d'application du prix)",
        "2886" => "Quantité gratuite produit fils",
        "2887" => "Quantité gratuite produit fils (code unité de mesure)",
        "2910" => "Code devise (Prix de vente suggéré)",
        "2911" => "Base d'application du prix",
        "2912" => "Date de début de validité du prix",
        "2913" => "Date de fin de validité du prix",
        "2914" => "Destinataire cible",
        "2930" => "SKU",
        "2931" => "Parent ou Enfant",
        "2932" => "CODE PARKOD",
        "2933" => "CODE LIGNE",
        "2934" => "TAG Création",
        "2935" => "Opération Commerciale",
        "2936" => "Coefficient prix",
        "2937" => "Prix Vente Public TTC",
        "2938" => "Point Rouge",
        "2939" => "Statut",
        "2940" => "Video Youtube",
        "2941" => "Type de produit",
        "2942" => "Exclusivité Magasin",
        "2943" => "Produit élu par les Clients",
        "2944" => "Produit élu par les Expertes",
        "2945" => "Produit élu par les Influenceurs",
        "2946" => "Meta Title SEO",
        "2947" => "Meta Description SEO",
        "2948" => "Avis Experte - Nom de l'experte",
        "2949" => "Avis Experte - Avis",
        "2951" => "Catégorie Niveau 1",
        "2952" => "Catégorie Niveau 2",
        "2953" => "Catégorie Niveau 3",
        "2954" => "Catégorie Niveau 4",
        "2956" => "Catégorie Niveau 1",
        "2957" => "Catégorie Niveau 2",
        "2958" => "Catégorie Niveau 3",
        "2959" => "Catégorie Niveau 4",
        "2960" => "Axe du produit",
        "2961" => "Concentration Parfum",
        "2962" => "quantité max de produits au panier",
        "2963" => "Exclusivité",
        "2964" => "Beauté Engagée",
        "2965" => "Coffret",
        "2966" => "Nom détaillé de la déclinaison",
        "2967" => "Attribut de déclinaison",
        "2968" => "Famille olfactive",
        "2969" => "Note de tête",
        "2970" => "Image Note de tête",
        "2971" => "Note de coeur",
        "2972" => "Image Note de coeur",
        "2973" => "Note de fond",
        "2974" => "Image Note de fond",
        "2975" => "Format",
        "2976" => "Formulation",
        "2977" => "Code couleur teinte Hexa",
        "2978" => "Effet attendu",
        "2979" => "Couvrance attendue",
        "2980" => "Action pour les soins corps",
        "2981" => "Action pour les soins homme",
        "2982" => "Effet pour les soins cheveux",
        "2983" => "Type de cheveux",
        "2984" => "Code EAN du produit full size",
        "2986" => "GTIN",
        "2988" => "Destinataire cible",
        "2989" => "Destinataire cible",
        "2997" => "Composés organiques volatils",
        "3" => "Référence interne",
        "300" => "Conditions particulières de conservation",
        "3008" => "Classification du matériau",
        "3009" => "Classification du matériau",
        "301" => "Conditions d'utilisation du produit",
        "3036" => "Numéro d'enregistrement",
        "3043" => "Code",
        "3044" => "Nom",
        "3048" => "Nombre d'unité pour chaque élément d'emballage",
        "310" => "Taux de houblon",
        "311" => "Pourcentage d'alcool par volume",
        "312" => "Millésime",
        "312_0" => "Produit millésimé",
        "313" => "Code droit d'accise",
        "32" => "GTIN du fils",
        "34" => "Quantité de fils",
        "348" => "Age maximum",
        "349" => "Nombre de joueurs maximum",
        "35" => "Unité de base",
        "350" => "Age minimum",
        "351" => "Nombre de joueurs minimum",
        "36" => "Unité consommateur",
        "37" => "Unité logistique",
        "38" => "Unité facturable",
        "39" => "Unité commandable",
        "399" => "Type de format spécial ou promotionnel",
        "40" => "Service",
        "400" => "Bénéfice produit",
        "401" => "Fonctionnalité liée au produit",
        "402" => "Description des accessoires inclus",
        "41" => "Statut fiche",
        "42" => "Circuit de distribution",
        "426" => "Nom du jury",
        "427" => "Nom du prix ou de la récompense",
        "428" => "Année du prix ou de la récompense",
        "429" => "Pile/batterie fournie",
        "430" => "Pile/batterie requise",
        "433" => "Batteries intégrées",
        "434" => "Couple électrochimique",
        "435" => "Type de pile/batterie",
        "436" => "Poids pile/batterie",
        "437" => "Poids pile/batterie (unité de mesure)",
        "438" => "Quantité de batteries intégrées",
        "439" => "Nombre de piles/batteries",
        "44" => "Présentoir garni",
        "442" => "Autonomie maximum de la batterie",
        "445" => "Agence de maintenance du standard/norme de certification",
        "447" => "Standard/Norme de certification",
        "448" => "Numéro du certificat BIO",
        "45" => "Date de fin de fabrication",
        "45_1" => "Date d\'annulation",
        "45_2" => "Date de changement effectif",
        "451" => "Date de fin de validité du certificat BIO du produit",
        "452" => "Date de début de validité du certificat BIO du produit",
        "46" => "Date de début de validité de la fiche produit",
        "47" => "Date de dernière mise à jour",
        "5" => "Pays",
        "50" => "Description de la variante produit",
        "51" => "Date de fin de transition entre les variantes",
        "52" => "Unité de vente marketing",
        "53" => "Nombre de pièces dans le set",
        "54" => "Nombre maximum d'unités consommateurs disponibles à la vente",
        "548" => "Etat physique de la matière",
        "55" => "Nombre minimum d'unités consommateurs disponibles à la vente",
        "550" => "Pourcentage maximal de Substance inflammable",
        "551" => "Pourcentage minimal de Substance inflammable",
        "552" => "Produits dangereux",
        "6" => "EAN poids variable",
        "604" => "Taux de TVA",
        "605" => "Marché cible",
        "608" => "Type de taxe",
        "611" => "Date de début de validité de la taxe",
        "612" => "Date de fin de validité de la taxe",
        "616" => "Montant de taxe",
        "620" => "Taux de taxe",
        "640" => "Matières premières irradiées",
        "645" => "Produit biologique",
        "646" => "Article génétiquement modifié",
        "648" => "Article irradié",
        "677" => "Mentions obligatoires complémentaires",
        "68" => "Article réapprovisionnable",
        "680" => "Code d'allégations de santé",
        "681" => "Allégation de santé",
        "687" => "Indice de protection solaire",
        "690" => "Ingrédients imprimés sur emballage",
        "692" => "Absence de substances marquées",
        "7" => "Code type",
        "713" => "Type d'emballage description",
        "714" => "Date de fin de disponibilité",
        "715" => "Date de début de disponibilité",
        "716" => "Année de disponibilité",
        "717" => "Nom de la saison",
        "718" => "Saison",
        "719" => "Age de la cible consommateur",
        "720" => "Tranche d'âge",
        "721" => "Cible consommateur",
        "73" => "Unité de mesure utilisée pour la Commande",
        "74" => "Maximum de commande",
        "75" => "Minimum de commande",
        "76" => "Multiple de commande",
        "77" => "Facteur de commande",
        "8_1" => "Marché cible",
        "8_2" => "Canal de distribution",
        "8_3" => "Connecteurs",
        "95" => "Délai de Livraison (jours)",
        "97" => "Libellé court",
        "98" => "Nom fonctionnel",
        "99" => "Libellé facture",
        "PK1" => "GTIN",
        "PK10" => "UVC / SPCB - UVC / Carton - SPCB / Carton - Carton / Palette - Box / Palette",
        "PK100" => "Elément d'emballage",
        "PK101" => "Code du type de processus de recyclage des emballages",
        "PK103" => "Matière première de l'emballage",
        "PK104" => "Pourcentage de la teneur en type de matière première",
        "PK107" => "Facteur de gerbage",
        "PK108" => "Type de gerbage",
        "PK109" => "Poids maximum de gerbage",
        "PK110" => "stackingWeightMaximum (unité de mesure)",
        "PK111" => "Type de motif d’empilement",
        "PK112" => "Niveau d'emballage",
        "PK113" => "Type d'emballage",
        "PK114" => "Type d'emballage description",
        "PK118" => "Emballage marqué retournable",
        "PK119" => "Classification du matériau",
        "PK12" => "Nombre de couches",
        "PK120" => "unité de mesure",
        "PK121" => "Nombre d'unité pour chaque élément d'emballage",
        "PK13" => "Type d'emballage description",
        "PK14" => "Niveau d'emballage",
        "PK16" => "Nbre de SPCB / Cartons / Box par couche",
        "PK17" => "Nombre d'UVC",
        "PK2" => "Référence interne",
        "PK20" => "Profondeur (cm)",
        "PK22" => "Largeur (cm)",
        "PK24" => "Hauteur (cm)",
        "PK25" => "Volume",
        "PK26" => "Article 'prêt à vendre'",
        "PK28" => "Poids brut (kg)",
        "PK3" => "Type d'unité commerciale",
        "PK30" => "Poids net (kg)",
        "PK32" => "Type d'emballage",
        "PK35" => "Support palette réutilisable",
        "PK36" => "Type de support palette",
        "PK37" => "Type de code à barres",
        "PK38" => "Instructions de manutention",
        "PK4" => "Statut fiche",
        "PK40" => "Unité de base",
        "PK41" => "Unité consommateur",
        "PK42" => "Unité logistique",
        "PK43" => "Service",
        "PK44" => "Présentoir garni",
        "PK45" => "Article réapprovisionnable",
        "PK46" => "Unité facturable",
        "PK47" => "Unité de mesure utilisée pour la Facture",
        "PK48" => "Libellé facture",
        "PK49" => "Unité commandable",
        "PK50" => "Minimum de commande",
        "PK51" => "Maximum de commande",
        "PK53" => "Multiple de commande",
        "PK54" => "Facteur de commande",
        "PK56" => "Délai minimum",
        "PK57" => "Facteur de gerbage",
        "PK59" => "Poids maximum de gerbage",
        "PK65" => "Type de matériaux d'emballage",
        "PK66" => "Matériau récupérable",
        "PK67" => "Quantité de matériau",
        "PK7" => "Libellé court",
        "PK71" => "Début de disponibilité à la commande",
        "PK72" => "Début de disponibilité à l'expédition",
        "PK73" => "Fin de disponibilité à la commande",
        "PK77" => "Fin de disponibilité à l'expédition",
        "PK8" => "Libellé long",
        "PK80" => "Fin de fabrication",
        "PK81" => "Annulation",
        "PK83" => "Type d'unité logistique (GENCOD)",
        "PK84" => "Type de présentoir",
        "PK86" => "Packaging feature",
        "PK9" => "EAN du niveau inférieur",
        "PK90" => "Quantité de matériau (code unité de mesure)",
        "PK91" => "Épaisseur du matériau",
        "PK92" => "Épaisseur du matériau (code unité de mesure)",
        "PK93" => "Code couleur du matériau",
        "PK94" => "Type de famille de code à barres",
        "PK98" => "Description du formulaire",
        "UL1" => "Dénomination de l'emballage",
        "UL12" => "Type",
        "UL13" => "GLN du (des) lieu/fonction",
        "UL14" => "Nom",
        "UL15" => "Adresse",
        "UL16" => "Code postal",
        "UL17" => "Ville",
        "UL18" => "Téléphone",
        "UL19" => "E-mail",
        "UL2" => "Indicateur promotionnel",
        "UL22" => "Dernière mise à jour",
        "UL3_1" => "Marché cible",
        "UL3_2" => "Canal de distribution",
        "UL3_3" => "Connecteurs",
        "UL4" => "Article 'prêt à vendre'",
        "UL5" => "Niveau logistique",
        "UL6" => "Article irrégulièrement emballé",
        "UL7" => "Nombre d'articles en profondeur",
        "UL9" => "Nombre d'articles en largeur",*/
    ];

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function signin(): bool
    {
        $response = $this->client->request('POST', 'https://api.equadis.com/EquadisRefonte/log/signin', [
            'json' => [
                'username' => '63465INFR',
                'password' => 'P@ssionApiProd1',
            ],
        ]);

        $cookies = $response->getHeaders()['set-cookie'] ?? [];
        foreach ($cookies as $cookie) {
            if (preg_match('/JSESSIONID=([^;]+)/', $cookie, $matches)) {
                $this->sessionCookie = $matches[1];
                return true;
            }
        }

        return false;
    }

    public function getUpdatedProductsGTINs(): array
    {
        if (!$this->sessionCookie) {
            throw new \Exception('Not authenticated');
        }

        $response = $this->client->request('GET', 'https://api.equadis.com/EquadisRefonte/product/getUpdatedProductsGTINs', [
            'headers' => [
                'Cookie' => 'JSESSIONID=' . $this->sessionCookie,
            ],
        ]);

        return $response->toArray();
    }

    public function getProducts(array $gtins): array
    {
        if (!$this->sessionCookie) {
            throw new \Exception('Not authenticated');
        }

        $response = $this->client->request('POST', 'https://api.equadis.com/EquadisRefonte/product/getProducts', [
            'headers' => [
                'Cookie' => 'JSESSIONID=' . $this->sessionCookie,
            ],
            'json' => [
                'paging' => [
                    'currentPage' => '1',
                    'numberOfRowsPerPage' => 100,
                ],
                'filters' => [
                    [
                        'field' => 'gtins',
                        'values' => $gtins,
                    ],
                ],
            ],
        ]);

        $products = $response->toArray();
        return $this->translateKeys($products['paginglist'] ?? []);
    }

    private function translateKeys(array $data): array
    {
        $translated = [];
        foreach ($data as $key => $value) {
            $translatedKey = $this->keyMapping[$key] ?? $key;
            if (is_array($value)) {
                $translated[$translatedKey] = $this->translateKeys($value);
            } else {
                $translated[$translatedKey] = $value;
            }
        }
        return $translated;
    }
}
# Creation 2 modules pour envoyer les orders a logisticien:
************** module Withings_ExportOrder *******************
Contient un observer permettant de recuperer data, preparer toutes les infos et appeler le service logisticien


************** module Withings_Logisticien *******************
Service Api recuperant le date et retourne l'identifiant associé au commande

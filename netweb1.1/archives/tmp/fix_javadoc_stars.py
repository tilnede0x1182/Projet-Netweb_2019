#!/usr/bin/env python3
"""
	Script de correction des commentaires Javadoc PHP.
	Ajoute les * en debut de chaque ligne interne des blocs /** ... */.

	Usage :
		python fix_javadoc_stars.py <dossier>
"""

# ==============================================================================
# Importations
# ==============================================================================
import os
import sys
import re

# ==============================================================================
# Donnees
# ==============================================================================
EXTENSIONS_PHP = [".php"]

# ==============================================================================
# Fonctions utilitaires
# ==============================================================================

"""
	Collecte tous les fichiers PHP dans un dossier recursivement.

	@param string $chemin_dossier Chemin du dossier racine
	@return list Liste des chemins de fichiers PHP
"""
def collecter_fichiers_php(chemin_dossier):
	fichiers = []
	for racine, dossiers, noms_fichiers in os.walk(chemin_dossier):
		for nom_fichier in noms_fichiers:
			extension = os.path.splitext(nom_fichier)[1].lower()
			if extension in EXTENSIONS_PHP:
				fichiers.append(os.path.join(racine, nom_fichier))
	return sorted(fichiers)

"""
	Lit un fichier avec gestion des encodages.

	@param string $chemin_fichier Chemin du fichier
	@return string Contenu du fichier ou None si erreur
"""
def lire_fichier(chemin_fichier):
	encodages = ["utf-8", "latin-1", "cp1252"]
	for encodage in encodages:
		try:
			with open(chemin_fichier, "r", encoding=encodage) as fichier:
				return fichier.read()
		except UnicodeDecodeError:
			continue
		except Exception as erreur:
			print(f"Erreur lecture {chemin_fichier} : {erreur}")
			return None
	return None

"""
	Ecrit le contenu dans un fichier.

	@param string $chemin_fichier Chemin du fichier
	@param string $contenu Contenu a ecrire
	@return bool True si succes
"""
def ecrire_fichier(chemin_fichier, contenu):
	try:
		with open(chemin_fichier, "w", encoding="utf-8") as fichier:
			fichier.write(contenu)
		return True
	except Exception as erreur:
		print(f"Erreur ecriture {chemin_fichier} : {erreur}")
		return False

"""
	Corrige un bloc Javadoc en uniformisant les * et l'indentation.

	@param string $bloc Bloc Javadoc complet
	@return string Bloc corrige
"""
def corriger_bloc_javadoc(bloc):
	lignes = bloc.split("\n")
	if len(lignes) <= 2:
		return bloc
	premiere_ligne = lignes[0]
	indentation_base = premiere_ligne[:len(premiere_ligne) - len(premiere_ligne.lstrip("\t "))]
	lignes_corrigees = []
	for index, ligne in enumerate(lignes):
		if index == 0:
			lignes_corrigees.append(ligne)
		elif index == len(lignes) - 1:
			ligne_stripped = ligne.lstrip("\t ")
			if ligne_stripped == "*/" or ligne_stripped == " */":
				lignes_corrigees.append(indentation_base + " */")
			else:
				lignes_corrigees.append(ligne)
		else:
			ligne_stripped = ligne.lstrip("\t ")
			if ligne_stripped.startswith("* "):
				contenu = ligne_stripped[2:]
				lignes_corrigees.append(indentation_base + " * " + contenu)
			elif ligne_stripped.startswith("*"):
				contenu = ligne_stripped[1:].lstrip()
				if contenu:
					lignes_corrigees.append(indentation_base + " * " + contenu)
				else:
					lignes_corrigees.append(indentation_base + " *")
			else:
				if ligne_stripped:
					lignes_corrigees.append(indentation_base + " * " + ligne_stripped)
				else:
					lignes_corrigees.append(indentation_base + " *")
	return "\n".join(lignes_corrigees)

"""
	Corrige tous les blocs Javadoc dans un contenu PHP.

	@param string $contenu Contenu du fichier PHP
	@return tuple (contenu_corrige, nombre_corrections)
"""
def corriger_javadoc_fichier(contenu):
	pattern = r'/\*\*[\s\S]*?\*/'
	nombre_corrections = [0]

	def remplacer(match):
		bloc_original = match.group(0)
		bloc_corrige = corriger_bloc_javadoc(bloc_original)
		if bloc_corrige != bloc_original:
			nombre_corrections[0] += 1
		return bloc_corrige

	contenu_corrige = re.sub(pattern, remplacer, contenu)
	return (contenu_corrige, nombre_corrections[0])

# ==============================================================================
# Fonction principale
# ==============================================================================

"""
	Traite tous les fichiers PHP d'un dossier.

	@param string $chemin_dossier Chemin du dossier racine
"""
def traiter_dossier(chemin_dossier):
	fichiers = collecter_fichiers_php(chemin_dossier)
	print(f"Fichiers PHP trouves : {len(fichiers)}")
	total_corrections = 0
	fichiers_modifies = 0
	for chemin_fichier in fichiers:
		contenu = lire_fichier(chemin_fichier)
		if contenu is None:
			continue
		contenu_corrige, nombre_corrections = corriger_javadoc_fichier(contenu)
		if nombre_corrections > 0:
			if ecrire_fichier(chemin_fichier, contenu_corrige):
				print(f"  {chemin_fichier} : {nombre_corrections} bloc(s) corrige(s)")
				total_corrections += nombre_corrections
				fichiers_modifies += 1
	print(f"\nTotal : {total_corrections} bloc(s) corrige(s) dans {fichiers_modifies} fichier(s)")

# ==============================================================================
# Main
# ==============================================================================

"""
	Point d'entree du programme.
"""
def main():
	if len(sys.argv) < 2:
		print("Usage : python fix_javadoc_stars.py <dossier>")
		sys.exit(1)
	chemin_dossier = sys.argv[1]
	if not os.path.isdir(chemin_dossier):
		print(f"Erreur : {chemin_dossier} n'est pas un dossier")
		sys.exit(1)
	traiter_dossier(chemin_dossier)

# ==============================================================================
# Lancement du programme
# ==============================================================================
if __name__ == "__main__":
	main()

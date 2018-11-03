var DIRECTION = {
	"DOWN"    : 0,
	"LEFT" : 1,
	"RIGHT" : 2,
	"UP"   : 3
}

var ANIMATION_TIME = 4;
var MOVE_TIME = 15;

function Character(url, x, y, direction, accountData) {

	//acount data
	this.accountData = accountData;
	this.displayData = true;
	
	//map data
	this.x = x; // (en cases)
	this.y = y; // (en cases)
	this.direction = direction;
	this.animationState = -1;
	
	// Chargement de l'image dans l'attribut image
	this.image = new Image();
	this.image.referenceDuPerso = this;
	this.image.onload = function() {
		if(!this.complete) 
			throw "Erreur de chargement du sprite nommé \"" + url + "\".";
		
		// Taille du personnage
		this.referenceDuPerso.largeur = this.width / 4;
		this.referenceDuPerso.hauteur = this.height / 4;
	}
	this.image.src = "rpg/charsets/" + url;
}

Character.prototype.draw = function(context) {
	var frame = 0; // Numéro de l'image à prendre pour l'animation
	var decalageX = 0, decalageY = 0; // Décalage à appliquer à la position du personnage
	if(this.animationState >= MOVE_TIME) {
		// Si le déplacement a atteint ou dépassé le temps nécessaire pour s'effectuer, on le termine
		this.animationState = -1;
	} else if(this.animationState >= 0) {
		// On calcule l'image (frame) de l'animation à afficher
		frame = Math.floor(this.animationState / ANIMATION_TIME);
		if(frame > 3) {
			frame %= 4;
		}
		
		// Nombre de pixels restant à parcourir entre les deux cases
		var pixelsAParcourir = 32 - (32 * (this.animationState / MOVE_TIME));
		
		// À partir de ce nombre, on définit le décalage en x et y.
		// NOTE : Si vous connaissez une manière plus élégante que ces quatre conditions, je suis preneur
		if(this.direction == DIRECTION.UP) {
			decalageY = pixelsAParcourir;
		} else if(this.direction == DIRECTION.DOWN) {
			decalageY = -pixelsAParcourir;
		} else if(this.direction == DIRECTION.LEFT) {
			decalageX = pixelsAParcourir;
		} else if(this.direction == DIRECTION.RIGHT) {
			decalageX = -pixelsAParcourir;
		}
		
		this.animationState++;
	}
	/*
	 * Si aucune des deux conditions n'est vraie, c'est qu'on est immobile, 
	 * donc il nous suffit de garder les valeurs 0 pour les variables 
	 * frame, decalageX et decalageY
	 */

	context.drawImage(
		this.image, 
		this.largeur * frame, this.direction * this.hauteur, // Point d'origine du rectangle source à prendre dans notre image
		this.largeur, this.hauteur, // Taille du rectangle source (c'est la taille du personnage)
		(this.x * 32) - (this.largeur / 2) + 16 + decalageX, (this.y * 32) - this.hauteur + 24 + decalageY, // Point de destination (dépend de la taille du personnage)
		this.largeur, this.hauteur // Taille du rectangle destination (c'est la taille du personnage)
	);
	
	//draw account data (name, hp, fp, ...)
	if(this.displayData)
	{
		//draw name
		context.fillStyle = "#ffffff";
		context.textAlign = 'center';
		var text = (this.accountData.admin ? "[GM] " : "") + this.accountData.name;
		var textWidth = context.measureText(text).width;
		context.fillText(text, (this.x * 32) - (this.largeur / 2) + 32 + decalageX /*- textWidth / 2*/, (this.y * 32) + (this.hauteur / 2) + decalageY);
		
		//hp
		context.fillStyle = "#ff0000";
		context.fillRect((this.x * 32) - (this.largeur / 2) + 16 + decalageX, (this.y * 32) - (this.hauteur / 2) + decalageY - 5, 32, 3);
		context.fillStyle = "#0be110";
		context.fillRect((this.x * 32) - (this.largeur / 2) + 16 + decalageX, (this.y * 32) - (this.hauteur / 2) + decalageY - 5, 32 * this.accountData.hp / this.accountData.max_hp, 3);

		//fp
		context.fillStyle = "#0054ff";
		context.fillRect((this.x * 32) - (this.largeur / 2) + 16 + decalageX, (this.y * 32) - (this.hauteur / 2) + 5 + decalageY - 5, 32, 3);
		context.fillStyle = "#00c6ff";
		context.fillRect((this.x * 32) - (this.largeur / 2) + 16 + decalageX, (this.y * 32) - (this.hauteur / 2) + 5 + decalageY - 5, 32 * this.accountData.fp / this.accountData.max_fp, 3);

	}
	
}

Character.prototype.getCoordonneesAdjacentes = function(direction)  {
	var coord = {'x' : this.x, 'y' : this.y};
	switch(direction) {
		case DIRECTION.DOWN : 
			coord.y++;
			break;
		case DIRECTION.LEFT : 
			coord.x--;
			break;
		case DIRECTION.RIGHT : 
			coord.x++;
			break;
		case DIRECTION.UP : 
			coord.y--;
			break;
	}
	return coord;
}
	
Character.prototype.deplacer = function(direction, map) {
	// On ne peut pas se déplacer si un mouvement est déjà en cours !
	if(this.animationState >= 0) {
		return false;
	}

	// On change la direction du personnage
	this.direction = direction;
		
	// On vérifie que la case demandée est bien située dans la carte
	var prochaineCase = this.getCoordonneesAdjacentes(direction);
	if(prochaineCase.x < 0 || prochaineCase.y < 0 || prochaineCase.x >= map.getWidth() || prochaineCase.y >= map.getHeight()) {
		// On retourne un booléen indiquant que le déplacement ne s'est pas fait, 
		// Ça ne coute pas cher et ca peut toujours servir
		return false;
	}
		
	// On effectue le déplacement
	this.animationState = 1;
	this.x = prochaineCase.x;
	this.y = prochaineCase.y;
		
	return true;
}
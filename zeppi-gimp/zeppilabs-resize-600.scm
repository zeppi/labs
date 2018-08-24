;
; The goal is to create snippets of code to resize an image 
; at 600 width and it must keep its proportions.
; 
; 
(
	define (zeppi-resize-600 p-image p-drawable)
	(let* (
			(imageWidth (car (gimp-image-width p-image)))
			(imageHeight (car (gimp-image-height p-image)))		
			(targeWidth 600)
			(targetHeigth)
		)
		
		(set! targetHeigth (* targeWidth (/ imageHeight imageWidth)))
		(gimp-image-scale p-image targeWidth targetHeigth)
		(gimp-layer-resize-to-image-size p-drawable)
	)
)

(script-fu-register 
	"zeppi-resize-600"
	"<Image>/Snipet/ZeppiResize600"
	"Resize to 600 width with proportional deformation"
	"Zeppi"
	"https://github.com/zeppi/labs"
	"2018"
	"*"
    SF-IMAGE "SF-IMAGE" 0
    SF-DRAWABLE "SF-DRAWABLE" 0
)
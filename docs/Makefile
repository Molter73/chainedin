all: latex

latex: chainedin.tex uml-diagrams output-dir
	tectonic -X compile \
		--synctex \
		--keep-logs \
		--keep-intermediates \
		--outdir build/ \
		chainedin.tex

uml-diagrams: uml.md
	plantuml -tlatex:nopreamble uml.md -o build

output-dir:
	@mkdir -p build/

clean:
	rm -rf build/

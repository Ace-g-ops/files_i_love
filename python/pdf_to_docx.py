import sys
from pdf2docx import Converter

def main():
    if len(sys.argv) != 3:
        print("Usage: pdf2docx.py <input_path> <output_path", file=sys.stderr)
        sys.exit(1)

        input_path = sys.argv[1]
        output_path = sys.argv[2]

        try:
            cv = Converter(input_path)
            cv.convert(output_path)
            cv.close()
        except Exception as e:
            print(f"Error: {e}", file=sys.stderr)
            sys.exit(1)

        sys.exit(0)

if __name__ == "__main__":
    main()

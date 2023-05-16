import sys
from discord_markdown.discord_markdown import convert_to_html

print(convert_to_html(sys.argv[1]))
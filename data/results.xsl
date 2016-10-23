<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
  <html>
  <body>
    <h2>Ergebnisse</h2>
    <table border="1">
      <tr bgcolor="#9acd32">
        <th>Zeit</th>
        <th>Anzahl Umdrehungen</th>
      </tr>
      <xsl:for-each select="root/results/result">
        <tr>
          <td><xsl:value-of select="time"/></td>
          <td><xsl:value-of select="flips"/></td>
        </tr>
      </xsl:for-each>
    </table>
  </body>
  </html>
</xsl:template>

</xsl:stylesheet> 
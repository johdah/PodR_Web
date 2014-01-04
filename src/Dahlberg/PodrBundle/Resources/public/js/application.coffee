$(document).foundation();

$("#player-toggle").click ->
  $("#player").toggle "slow", ->
    # Animation complete.
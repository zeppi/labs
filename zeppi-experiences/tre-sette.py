import unittest
import copy
from enum import Enum
import random
       
class Signs(Enum):
    """The north, the cards use the Italian signs"""
    SWORDS = "Epee"
    DENARII = "Or"
    STICKS = "Baton"
    CUPS = "Coupe"

class Cards(Enum):
    AS = "AS"
    TWO = "Deux"
    THREE = "Trois"
    FOUR = "Quatre"
    FIVE = "Cinq"
    SIX = "Six"
    SEVEN = "Sept"
    LADY = "Dame"
    KNIGHT = "Chevalier"
    KING = "Rois"
    
class NeapolitanCard:
    """ Playing cards appeared in Italy in the second half of the fourteenth century,
    their presence is attested in Florence from 1377. The playing cards of Italy have 
    many regional variations, as much in the signs and the graphic aspect of the cards 
    as in their number. Overall, styles fall into four main categories:
    
    -  in the north, the cards use the Italian signs: cups, sticks, denarii and swords.
    -  in the south, they make use of the Spanish signs, which bear the same names as the 
       Italian signs but have a distinct graphic style.
    -  in the north-west, the French signs are used: hearts, diamonds, spades and clubs.
    -  in the northeast, in the Italian Tyrol, the cards use the German signs: hearts, 
       bells, acorns and leaves.

    The decks consist mainly of 40 cards: 1 to 7 and three figures 3. However, some varieties
    have 52. The figures differ depending on the region. Cards are almost never numbered.
    """
    def __init__(self, uid, sign, card):
        self.uid = uid
        self.signs = sign
        self.card = card
        
    def show(self):
        print(self.uid + '| ' + self.family + '-' + self.name)
           
class TreSette:
    """ Tressette or Tresette (trešeta in Croatian and Montenegrin) is a 40-card, trick-taking 
    card game. It is one of Italy's major national card games, together with Scopa and Briscola. 
    It is also popular in the regions that were once controlled by the Italian predecessor states, 
    such as Albania, Montenegro, coastal Slovenia (Slovene Littoral) and coastal Croatia 
    (Istria and Dalmatia). The Austrian game Trischettn as historically played in South Tyrol is 
    also a derivative, albeit played with a 32-card German-suited deck.
    """
    def sumCardsValue(deck):         
        r = 0 
        p = 0
        for c in deck:
            if c.value == 1:
                p += 1
            elif c.value > 0:
                r += 1
                if r == 3:
                    p += 1
                    r = 0

        return p           
        
    def getDecks(self):
        return [        
            TreSetteCard('0001-0', Signs.SWORDS, Cards.THREE, 10, 0.333),
            TreSetteCard('0001-1', Signs.SWORDS, Cards.TWO, 9, 0.333),
            TreSetteCard('0001-2', Signs.SWORDS, Cards.AS, 8, 1),
            TreSetteCard('0001-3', Signs.SWORDS, Cards.KING, 7, 0.333),
            TreSetteCard('0001-4', Signs.SWORDS, Cards.KNIGHT, 6, 0.333),
            TreSetteCard('0001-5', Signs.SWORDS, Cards.LADY, 5, 0.333),
            TreSetteCard('0001-6', Signs.SWORDS, Cards.SEVEN, 4, 0),
            TreSetteCard('0001-7', Signs.SWORDS, Cards.SIX, 3, 0),
            TreSetteCard('0001-8', Signs.SWORDS, Cards.FIVE, 2, 0),
            TreSetteCard('0001-9', Signs.SWORDS, Cards.FOUR, 1, 0),            
            TreSetteCard('0010-0', Signs.STICKS, Cards.THREE, 10, 0.333),
            TreSetteCard('0010-1', Signs.STICKS, Cards.TWO, 9, 0.333),
            TreSetteCard('0010-2', Signs.STICKS, Cards.AS, 8, 1),
            TreSetteCard('0010-3', Signs.STICKS, Cards.KING, 7, 0.333),
            TreSetteCard('0010-4', Signs.STICKS, Cards.KNIGHT, 6, 0.333),
            TreSetteCard('0010-5', Signs.STICKS, Cards.LADY, 5, 0.333),
            TreSetteCard('0010-6', Signs.STICKS, Cards.SEVEN, 4, 0),
            TreSetteCard('0010-7', Signs.STICKS, Cards.SIX, 3, 0),
            TreSetteCard('0010-8', Signs.STICKS, Cards.FIVE, 2, 0),
            TreSetteCard('0010-9', Signs.STICKS, Cards.FOUR, 1, 0),
            TreSetteCard('0100-0', Signs.CUPS, Cards.THREE, 10, 0.333),
            TreSetteCard('0100-1', Signs.CUPS, Cards.TWO, 9, 0.333),
            TreSetteCard('0100-2', Signs.CUPS, Cards.AS, 8, 1),
            TreSetteCard('0100-3', Signs.CUPS, Cards.KING, 7, 0.333),
            TreSetteCard('0100-4', Signs.CUPS, Cards.KNIGHT, 6, 0.333),
            TreSetteCard('0100-5', Signs.CUPS, Cards.LADY, 5, 0.333),
            TreSetteCard('0100-6', Signs.CUPS, Cards.SEVEN, 4, 0),
            TreSetteCard('0100-7', Signs.CUPS, Cards.SIX, 3, 0),
            TreSetteCard('0100-8', Signs.CUPS, Cards.FIVE, 2, 0),
            TreSetteCard('0100-9', Signs.CUPS, Cards.FOUR, 1, 0),        
            TreSetteCard('1000-0', Signs.DENARII, Cards.THREE, 10, 0.333),
            TreSetteCard('1000-1', Signs.DENARII, Cards.TWO, 9, 0.333),
            TreSetteCard('1000-2', Signs.DENARII, Cards.AS, 8, 1),
            TreSetteCard('1000-3', Signs.DENARII, Cards.KING, 7, 0.333),
            TreSetteCard('1000-4', Signs.DENARII, Cards.KNIGHT, 6, 0.333),
            TreSetteCard('1000-5', Signs.DENARII, Cards.LADY, 5, 0.333),
            TreSetteCard('1000-6', Signs.DENARII, Cards.SEVEN, 4, 0),
            TreSetteCard('1000-7', Signs.DENARII, Cards.SIX, 3, 0),
            TreSetteCard('1000-8', Signs.DENARII, Cards.FIVE, 2, 0),
            TreSetteCard('1000-9', Signs.DENARII, Cards.FOUR, 1, 0)]
            
    def distCards(self):
        decks = self.getDecks()
        hands = [[],[],[],[]]
        for h in range(4):
            for i in range(10):
                c = decks.pop(random.randint(0,len(decks)-1))
                hands[h].append(c.uid)
                
        return hands
            
    def possibleThrowCardForFirstPlayer(cards):
        return copy.deepcopy(cards)

    def possibleThrowCardForNextPlayer(card_throw_first, cards):
        p = []
        for i in cards:
            if card_throw_first.signs == i.signs:
                p.append(i)
                
        if len(p) == 0:
            return TreSette.possibleThrowCardForFirstPlayer(cards)
            
        return copy.deepcopy(p)
    
    def winningRound(cards):
        won = cards[0]
        signs = cards[0].signs
        
        for i in range(4):
            if cards[i].signs == signs and cards[0].power > won.power:
                won = cards[i]

        return won
        
    def makeSimpleDecisionOrForce(my_univers_of_possible, force=None):
        my_univers_of_possible.sort(key=lambda x: x.power)
        
        if force != None:
            for i in my_univers_of_possible:
                if i.uid == force:
                    return i
                    
        return my_univers_of_possible[0]
            
class TreSetteCard(NeapolitanCard):
    def __init__(self, uid, sign, card, power, value):
        super().__init__(uid, sign, card)
        self.power = power
        self.value = value
        
class Player:
    def __init__(self, uid, name):
        self.uid = uid
        self.name = name
        self.card_in_hand = []
        
    def show(self):
        print(self.uid + '| ' + self.name)        

    def takeCard(self, card):
        for c in self.card_in_hand:
            if c.uid == card.uid:
                return False
                
        self.card_in_hand.append(card)
        return True

    def throwCard(self, card):
        for c in self.card_in_hand:
            if c.uid == card.uid:
                self.card_in_hand.remove(c)
                return True
                
        return False
        
    def retrieveCardInHand(self):
        return self.card_in_hand
        
class Team:
    def __init__(self, uid, player_a, player_b):
        self.uid = uid
        self.player_a = player_a
        self.player_b = player_b
        self.card_won = []
        self.last_hand = False
            
    def show(self):
        print(self.uid)             
        
    def earned(self):
        p = TreSette.sumCardsValue(self.card_won)
        if self.last_hand is True:
            p += 1
            
        return p        
    
class Round:
    def __init__(self, player_a, player_b, player_c, player_d):
        self.player_a = copy.deepcopy(player_a)
        self.player_b = copy.deepcopy(player_b)
        self.player_c = copy.deepcopy(player_c)
        self.player_d = copy.deepcopy(player_d)

        self.on_the_stage = []
        
        self.univers_a = TreSette.possibleThrowCardForFirstPlayer(self.player_a.retrieveCardInHand()) 
        self.univers_b = []
        self.univers_c = []
        self.univers_d = []
        
    def show(self):
        print("Round situation__________________________________")
        print("Player a: ", self.player_a.uid)
        print("Player b: ", self.player_b.uid)
        print("Player c: ", self.player_c.uid)
        print("Player d: ", self.player_d.uid)
        
        univers = ""
        for i in self.univers_a:
            univers += ',' + i.uid
        print("Univers a: ", univers)

        univers = ""
        for i in self.univers_b:
            univers += ',' + i.uid
        print("Univers b: ", univers)

        univers = ""
        for i in self.univers_c:
            univers += ',' + i.uid
        print("Univers c: ", univers)

        univers = ""
        for i in self.univers_d:
            univers += ',' + i.uid
        print("Univers d: ", univers)        
        
        print("On the stage: ", self.getOnTheStageString())  

    def getOnTheStageString(self):
        card = []
        for i in self.on_the_stage:
            card.append(i.uid)
            
        return ",".join(card)
        
    def run(self, p1=None, p2=None, p3=None, p4=None):
        self.on_the_stage = []
        
        self.univers_a = TreSette.possibleThrowCardForFirstPlayer(self.player_a.retrieveCardInHand()) 
        self.univers_b = []
        self.univers_c = []
        self.univers_d = []
        
        p1_decision = TreSette.makeSimpleDecisionOrForce(self.univers_a, p1)
        self.on_the_stage.append(p1_decision)
        
        self.univers_b = TreSette.possibleThrowCardForNextPlayer(p1_decision, self.player_b.retrieveCardInHand()) 
        self.on_the_stage.append(TreSette.makeSimpleDecisionOrForce(self.univers_b, p2))
        
        self.univers_c = TreSette.possibleThrowCardForNextPlayer(p1_decision, self.player_c.retrieveCardInHand()) 
        self.on_the_stage.append(TreSette.makeSimpleDecisionOrForce(self.univers_c, p3))
        
        self.univers_d = TreSette.possibleThrowCardForNextPlayer(p1_decision, self.player_d.retrieveCardInHand()) 
        self.on_the_stage.append(TreSette.makeSimpleDecisionOrForce(self.univers_d, p4))
        
        winner = TreSette.winningRound(self.on_the_stage)
        
        if winner == self.on_the_stage[0]:
            return Round(self.player_a, self.player_b, self.player_c, self.player_d)
            
        if winner == self.on_the_stage[1]:
            return Round(self.player_b, self.player_c, self.player_d, self.player_a)
            
        if winner == self.on_the_stage[2]:
            return Round(self.player_c, self.player_d, self.player_a, self.player_b)
            
        if winner == self.on_the_stage[3]:
            return Round(self.player_d, self.player_a, self.player_b, self.player_c)            

class PossibleRound:
    def __init__(self, round):
        self.round = round
        self.possible = {}
           
    def retrievePossibleRounds(self):
        self.possible = {}
        
        for univers_a in self.round.univers_a:  
            r = copy.deepcopy(self.round)
            r.run(univers_a.uid)
            self.possible[r.getOnTheStageString()] = r

        tmp = []
        for from_a in self.possible.values():            
            if len(from_a.univers_b) > 1:
                for b_idx in range(1, len(from_a.univers_b)):                    
                    r = copy.deepcopy(self.round)
                    r.run(from_a.on_the_stage[0].uid, from_a.univers_b[b_idx].uid)
                    tmp.append(r)                 

        self.__appendIfNotExist(copy.deepcopy(tmp))
            
        tmp = []
        for from_ab in self.possible.values():
            if len(from_ab.univers_c) > 1:                
                for c_idx in range(1, len(from_ab.univers_c)):                    
                    r = copy.deepcopy(self.round)
                    r.run(from_ab.on_the_stage[0].uid, from_ab.on_the_stage[1].uid, from_ab.univers_c[c_idx].uid)
                    tmp.append(r)   
        
        self.__appendIfNotExist(copy.deepcopy(tmp))
            
        tmp = []
        for from_abc in self.possible.values():
            if len(from_abc.univers_d) > 1:                
                for d_idx in range(1, len(from_abc.univers_d)):                    
                    r = copy.deepcopy(self.round)
                    r.run(from_abc.on_the_stage[0].uid, from_abc.on_the_stage[1].uid, from_abc.on_the_stage[2].uid, from_ab.univers_d[d_idx].uid)
                    tmp.append(r)   
        
        self.__appendIfNotExist(copy.deepcopy(tmp))
        
        return self.possible
            
    def __appendIfNotExist(self, tmp):
        for p in tmp:
            self.possible[p.getOnTheStageString()] = p
            
class Game:
    def __init__(self, team_a, team_b, dist=None):
        self.team_a = team_a
        self.team_b = team_b
        
        if dist != None:
            cards = TreSette()
            decks = cards.getDecks()
            
            for i in decks:
                if i.uid in dist[0]:               
                    self.team_a.player_a.card_in_hand.append(i)

                if i.uid in dist[1]:               
                    self.team_b.player_a.card_in_hand.append(i)

                if i.uid in dist[2]:               
                    self.team_a.player_b.card_in_hand.append(i)

                if i.uid in dist[3]:               
                    self.team_b.player_b.card_in_hand.append(i)                    
        else:
            self.dist()
        
        ## 10 rounds for ending the game
        self.rounds = []
        
    def dist(self):
        pass
        
    def playRound(self, p1=None, p2=None, p3=None, p4=None):
        if len(self.rounds) == 10:
            return None
            
        r = Round(
            self.team_a.player_a, 
            self.team_b.player_a, 
            self.team_a.player_b, 
            self.team_b.player_b)
            
        play = r.run(p1, p2, p3, p4)
        
        self.rounds.append({
            'round': r,
            'play': play
        })
        
        if play.player_a.uid == self.team_a.player_a.uid or play.player_a.uid == self.team_a.player_b.uid:
            for i in r.on_the_stage:
                self.team_a.card_won.append(i)
                
            if len(self.rounds) == 10:
                self.team_a.last_hand = True
        else:
            for i in r.on_the_stage:
                self.team_b.card_won.append(i)
                
            if len(self.rounds) == 10:
                self.team_b.last_hand = True                
        
        self.team_a.player_a.throwCard(r.on_the_stage[0])
        self.team_b.player_a.throwCard(r.on_the_stage[1])
        self.team_a.player_b.throwCard(r.on_the_stage[2])
        self.team_b.player_b.throwCard(r.on_the_stage[3])       

class TestCard(unittest.TestCase):

    def test_card(self):
        c = TreSetteCard('1', '1', 'test', 2, 3)
        self.assertEqual(c.uid, '1')
        self.assertEqual(c.power, 2)
        
    def test_decks(self):
        d = TreSette()
        self.assertEqual(len(d.getDecks()), 40)
        self.assertEqual(TreSette.sumCardsValue(d.getDecks()), 10)
        p = d.distCards();
        
    def test_player(self):
        p = Player('001', 'Test')
        c = TreSetteCard('1', '1', 'test', 2, 3)
        p.takeCard(c)
        
        self.assertEqual(len(p.retrieveCardInHand()), 1)
        self.assertFalse(p.takeCard(c))
        self.assertEqual(len(p.retrieveCardInHand()), 1)
        self.assertTrue(p.throwCard(c))
        self.assertEqual(len(p.retrieveCardInHand()), 0)
        self.assertFalse(p.throwCard(c))
        
    def test_list_and_card(self):    
        set_of_cards = [
            TreSetteCard('1', '1', 'test1', 2, 3),
            TreSetteCard('2', '1', 'test2', 2, 3),
            TreSetteCard('3', '3', 'test3', 2, 3),
        ]
        
        list001 = TreSette.possibleThrowCardForFirstPlayer(set_of_cards)        
        set_of_cards.pop()
        self.assertEqual(len(list001), 3)
        
        for i in set_of_cards:
            if i.uid == '1':
                i.card = 'changed'
                
        for i in list001:
            if i.uid == '1':
                self.assertEqual(i.card, 'test1')
        
    def test_round(self):
        player_a = Player('001', 'P1')
        player_b = Player('002', 'P2')
        player_c = Player('003', 'P3')
        player_d = Player('004', 'P4')
        
        player_a.takeCard(TreSetteCard('0001-1', Signs.SWORDS, Cards.TWO, 9, 0.333))
        player_a.takeCard(TreSetteCard('0100-7', Signs.CUPS, Cards.SIX, 3, 0))

        player_b.takeCard(TreSetteCard('0001-5', Signs.SWORDS, Cards.LADY, 5, 0.333))
        player_b.takeCard(TreSetteCard('1000-5', Signs.DENARII, Cards.LADY, 5, 0.333))

        player_c.takeCard(TreSetteCard('1000-9', Signs.DENARII, Cards.FOUR, 1, 0))
        player_c.takeCard(TreSetteCard('0001-3', Signs.SWORDS, Cards.KING, 7, 0.333))

        player_d.takeCard(TreSetteCard('0001-0', Signs.SWORDS, Cards.THREE, 10, 0.333))
        player_d.takeCard(TreSetteCard('0001-7', Signs.SWORDS, Cards.SIX, 3, 0))
        
        round = Round(player_a, player_b, player_c, player_d)
        self.assertEqual(len(round.univers_a), 2)
       
        option = PossibleRound(round)
        possibles = option.retrievePossibleRounds()
        
        self.assertEqual(len(possibles.values()), 10)
    
    def test_game(self):
        player_a = Player('001', 'P1')
        player_b = Player('002', 'P2')
        player_c = Player('003', 'P3')
        player_d = Player('004', 'P4')
        
        team_a = Team('T01', player_a, player_c)
        team_b = Team('T02', player_b, player_d)
        
        game = Game(team_a, team_b, [['0001-1', '0100-7'], ['1000-9', '0001-3'], ['0001-5', '1000-5'], ['0001-0', '0001-7']])
        
        self.assertEqual(len(team_a.player_a.card_in_hand), 2)
        
        game.playRound()        
        self.assertEqual(len(team_a.player_a.card_in_hand), 1)
        
        hands = [
            # 5 - coupe, 6 - épée, 3 - coupe, dame - coupe, 4 - coupe, 
            ['0100-8', '0001-7', '0100-0', '0100-5', '0100-9', '0001-6', '0100-4', '0010-0', '0010-4', '1000-3'], 
            ['0100-7', '0001-4', '0001-0', '0001-5', '1000-5', '1000-2', '0010-5', '0100-1', '0010-9', '0001-8'], 
            ['0001-9', '1000-1', '0010-2', '0010-7', '1000-6', '0100-6', '0010-1', '0100-3', '0001-3', '1000-7'], 
            ['0001-2', '0001-1', '1000-9', '1000-8', '0100-2', '1000-4', '0010-8', '1000-0', '0010-6', '0010-3']]
    
if __name__ == '__main__':
    unittest.main()
  
    